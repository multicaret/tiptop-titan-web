<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\BranchResource;
use App\Http\Resources\CartResource;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\FoodCategoryResource;
use App\Http\Resources\GroceryCategoryParentResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\RemoteConfigResource;
use App\Http\Resources\SlideResource;
use App\Models\Branch;
use App\Models\Cart;
use App\Models\Currency;
use App\Models\Location;
use App\Models\Order;
use App\Models\Preference;
use App\Models\RemoteConfig;
use App\Models\Slide;
use App\Models\Taxonomy;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HomeController extends BaseApiController
{
    public function remoteConfigs(Request $request): JsonResponse
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
            return $this->respond([
                'configs' => new RemoteConfigResource($bootConfigurations),
                'defaultChannel' => Preference::retrieveValue('default_channel'),
            ]);
        }

        return $this->respond([
            'configs' => null,
            'defaultChannel' => Preference::retrieveValue('default_channel'),
        ], null, 'Things are fine, you may pass!');
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

        // Grocery Related Initializers
        $distance = 0;
        $branch = null;
        // Food Related Initializers
        $foodBranches = [];

        // GeoLocation handling
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        // Region & City
        $cityId = null;
        $regionId = null;
        if ( ! is_null($user) && ! is_null($selectedAddress = $request->input('selected_address_id'))) {
            $selectedAddress = Location::find($selectedAddress);
            if (is_null($selectedAddress)) {
                return $this->respondNotFound('Address not found!');
            } else {
                if (is_null($selectedAddress->region)) {
                    return $this->respondNotFound('Address without a City!');
                }
                if (is_null($selectedAddress->city)) {
                    return $this->respondNotFound('Address without a Neighborhood!');
                }
                $regionId = $selectedAddress->region->id;
                $cityId = $selectedAddress->city->id;
            }
            $latitude = $selectedAddress->latitude;
            $longitude = $selectedAddress->longitude;
            $user->selected_address_id = $selectedAddress->id;
            $user->save();
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
                $cart = Cart::retrieve(
                    $request->input('selected_food_chain_id'),
                    $request->input('selected_food_branch_id'),
                    $user->id
                );
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

        $currentCurrency = Currency::find(config('defaults.currency.id'));
        if (localization()->getCurrentLocale() == 'en') {
            $currentCurrency->symbol = 'IQD';
        }
        $currencyResource = new CurrencyResource($currentCurrency);

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
            'currentCurrency' => $currencyResource,
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
     * @param  User|null  $user
     * @param  int|null  $regionId
     * @param  int|null  $cityId
     * @return AnonymousResourceCollection
     */
    private function retrieveSlides(
        string $channel,
        ?User $user,
        ?int $regionId,
        ?int $cityId
    ): AnonymousResourceCollection {
        $slideChannel = Slide::CHANNEL_GROCERY_OBJECT;
        if ($channel == config('app.app-channels.food')) {
            $slideChannel = Slide::CHANNEL_FOOD_OBJECT;
        }
        $hasBeenAuthenticated = ! is_null($user) ? Slide::TARGET_LOGGED_IN : Slide::TARGET_GUEST;
        $slides = Slide::getModel();

        $slides = $slides
            ->where(function ($query) use ($slideChannel) {
                $query->where('channel', $slideChannel)
                      ->orWhere('channel', Slide::CHANNEL_FOOD_AND_GROCERY_OBJECT);
            })
            ->where(function ($query) use ($hasBeenAuthenticated) {
                $query->where('has_been_authenticated', $hasBeenAuthenticated)
                      ->orWhere('has_been_authenticated', Slide::TARGET_EVERYONE);
            })
            ->whereDate('expires_at', '>=', now())
            ->whereDate('begins_at', '<=', now());

        if ( ! is_null($regionId)) {
            $slides = $slides->where(function ($query) use ($regionId) {
                $query->where('region_id', $regionId)
                      ->orWhereNull('region_id');
            });
        } else {
            $slides = $slides->whereNull('region_id');
        }

        if ( ! is_null($cityId)) {
            $slides = $slides->where(function ($query) use ($cityId) {
                $query->where('city_id', $cityId)
                      ->orWhereNull('city_id');
            });
        } else {
            $slides = $slides->whereNull('city_id');
        }

        $slides = $slides->active()->get();

        return SlideResource::collection($slides);
    }
}
