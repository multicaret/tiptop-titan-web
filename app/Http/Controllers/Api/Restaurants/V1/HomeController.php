<?php

namespace App\Http\Controllers\Api\Restaurants\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\RemoteConfigResource;
use App\Http\Resources\BranchResource;
use App\Http\Resources\CartResource;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\FoodCategoryResource;
use App\Http\Resources\GroceryCategoryParentResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\SlideResource;
use App\Models\RemoteConfig;
use App\Models\Branch;
use App\Models\Cart;
use App\Models\Currency;
use App\Models\Location;
use App\Models\Order;
use App\Models\Slide;
use App\Models\Taxonomy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends BaseApiController
{
    public function boot(Request $request): JsonResponse
    {
        /*$validationRules = [
            'build_number' => 'required|numeric',
            'platform' => 'required|min:3|max:20',
        ];

        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }*/

        $forceUpdateMethod = RemoteConfig::FORCE_UPDATE_METHOD_DISABLED;
        $buildNumber = $request->input('build_number');
        $platform = $request->input('platform');

        $bootConfigurations = RemoteConfig::where('platform_type', strtolower($platform))
                                          ->where('build_number', $buildNumber)
                                          ->first();

//dd($bootConfigurations->data_translated);
        if ( ! is_null($bootConfigurations)) {
            return $this->respond(new RemoteConfigResource($bootConfigurations));
        }

        return $this->respondWithMessage('Things are fine, you may pass!');
    }

    public function root()
    {
        return $this->respondWithMessage('Welcome to '.config('app.name'));
    }

    public function index(Request $request)
    {
        $validationRules = [
            'channel' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ];

        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        $user = auth('sanctum')->user();
        $channel = strtolower($request->input('channel'));
        $categories = [];
        $cart = null;
        $activeOrders = null;
        $totalActiveOrders = 0;

        $eta = '30-45';
        $etaUnit = 'min';
        $noAvailabilityMessage = '';

        $selectedAddress = null;
        $cityId = null;
        $regionId = null;

        // Grocery Related Initializers
        $distance = 0;
        $branch = null;
        // Food Related Initializers
        $foodBranches = [];

        // GeoLocation handling
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        if ( ! is_null($user) && ! is_null($selectedAddress = $request->input('selected_address_id'))) {
            $selectedAddress = Location::find($selectedAddress);
            if (is_null($selectedAddress)) {
                return $this->respondNotFound('Address not found!');
            }
            $latitude = $selectedAddress->latitude;
            $longitude = $selectedAddress->longitude;
            $user->selected_address_id = $selectedAddress->id;
            $user->save();
        }

        // Todo: fill the Region & City after we finishing the GeoFencing from Jadid.
        if ( ! is_null($selectedAddress)) {
            $cityId = $selectedAddress->city->id;
            $regionId = $selectedAddress->region->id;
        }
        $slides = $this->retrieveSlides($channel, $user, $regionId, $cityId);


        if ($channel == config('app.app-channels.grocery')) {

            $categories = cache()->rememberForever('all_grocery_categories_with_products', function () {
                $groceryParentCategories = Taxonomy::active()->groceryCategories()->parents()->get();

                return GroceryCategoryParentResource::collection($groceryParentCategories);
            });

            [$distance, $branch] = Branch::getClosestAvailableBranch($latitude, $longitude);
            if ( ! is_null($branch)) {
                if ( ! is_null($user)) {
                    $cart = Cart::retrieve($branch->chain_id, $branch->id, $user->id);
                    $activeOrders = Order::groceries()->whereUserId($user->id)
                                         ->whereNotIn('status', [
                                             Order::STATUS_CANCELLED,
                                             Order::STATUS_DELIVERED,
                                         ])
                                         ->whereChainId($branch->chain_id)
                                         ->latest();
                    $activeOrdersCount = $activeOrders->count();
                    $activeOrders = OrderResource::collection($activeOrders->take(4)->get());
                    $totalActiveOrders = $activeOrdersCount;
                }
            } else {
                // It's too late no branch is open for now, so sorry
                // No Branch
                // No Cart
                $noAvailabilityMessage = 'No Branch is not available, please check back again at 08:00 am';
            }

            // Always in grocery the EA is 20-30, for dynamic values use "->distance" attribute from above.
            $eta = '20-30';
        } else {
            $categories = cache()->rememberForever('all_food_categories', function () {
                $categories = Taxonomy::active()->foodCategories()->get();

                return FoodCategoryResource::collection($categories);
            });
            $foodBranches = Branch::foods()
                                  ->active()
                                  ->latest('published_at')
                                  ->get();

            if ( ! is_null($user)) {
                $cart = Cart::retrieve(null, null, $user->id);
                $activeOrders = Order::foods()->whereUserId($user->id)
                                     ->whereNotIn('status', [
                                         Order::STATUS_CANCELLED,
                                         Order::STATUS_DELIVERED,
                                     ])
                                     ->where('type', Order::CHANNEL_FOOD_OBJECT)
                                     ->latest();
                $activeOrdersCount = $activeOrders->count();
                $activeOrders = OrderResource::collection($activeOrders->take(4)->get());
                $totalActiveOrders = $activeOrdersCount;
            }
            if (is_null($foodBranches)) {
                $noAvailabilityMessage = 'No Restaurants is are now open, please check back again at 08:00 am';
            }
        }

        return $this->respond([
            'estimated_arrival_time' => [
                'value' => $eta,
                'unit' => $etaUnit,
            ],
            'cart' => is_null($cart) ? null : new CartResource($cart),
            'slides' => $slides,
            'categories' => $categories,
            'activeOrders' => $activeOrders,
            'totalActiveOrders' => $totalActiveOrders,
            'currentCurrency' => new CurrencyResource(Currency::find(config('defaults.currency.id'))),
            'noAvailabilityMessage' => $noAvailabilityMessage,
            // Grocery Related
            'branch' => is_null($branch) ? null : new BranchResource($branch),
            'distance' => $distance,
            // Food Related
            'restaurants' => is_null($foodBranches) ? null : BranchResource::collection($foodBranches),
        ]);
    }

    /**
     * @param  string  $channel
     * @param  \App\Models\User|null  $user
     * @param  int|null  $regionId
     * @param  int|null  $cityId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    private function retrieveSlides(
        string $channel,
        ?\App\Models\User $user,
        ?int $regionId,
        ?int $cityId
    ): \Illuminate\Http\Resources\Json\AnonymousResourceCollection {
        $slideChannel = Slide::CHANNEL_GROCERY_OBJECT;
        if ($channel == config('app.app-channels.food')) {
            $slideChannel = Slide::CHANNEL_FOOD_OBJECT;
        }
        $hasBeenAuthenticated = ! is_null($user) ? Slide::TARGET_LOGGED_IN : Slide::TARGET_GUEST;
        $slides = Slide::whereIn('channel', [$slideChannel, Slide::TYPE_FOOD_AND_GROCERY_OBJECT])
                       ->whereIn('has_been_authenticated', [$hasBeenAuthenticated, Slide::TARGET_EVERYONE])
                       ->whereDate('expires_at', '>', now())
                       ->whereDate('begins_at', '<', now());

        if ( ! is_null($regionId)) {
            $slides = $slides->where('region_id', $regionId);
        }
        if ( ! is_null($cityId)) {
            $slides = $slides->where('city_id', $cityId);
        }

        return SlideResource::collection($slides->get());
    }
}
