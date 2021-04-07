<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\BootResource;
use App\Http\Resources\BranchResource;
use App\Http\Resources\CartResource;
use App\Http\Resources\FoodBranchResource;
use App\Http\Resources\FoodCategoryResource;
use App\Http\Resources\GroceryCategoryParentResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\SlideResource;
use App\Models\Boot;
use App\Models\Branch;
use App\Models\Cart;
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

        $forceUpdateMethod = Boot::FORCE_UPDATE_METHOD_DISABLED;
        $buildNumber = $request->input('build_number');
        $platform = $request->input('platform');

        $bootConfigurations = Boot::where('platform_type', strtolower($platform))
                                  ->where('build_number', $buildNumber)
                                  ->first();

//dd($bootConfigurations->data_translated);
        if ( ! is_null($bootConfigurations)) {
            return $this->respond(new BootResource($bootConfigurations));
        }

        return $this->respondWithMessage('Things are fine, pass you twat!');
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

        $address = Location::find($request->input('selected_address_id'));
        if (is_null($address)) {
            $cityId = null;
            $regionId = null;
        } else {
            $cityId = optional($address->city)->id;
            $regionId = optional($address->region)->id;
        }
        if ($channel == config('app.app-channels.food')) {
            $slideChannel = Slide::CHANNEL_FOOD_OBJECT;
        } elseif ($channel == config('app.app-channels.grocery')) {
            $slideChannel = Slide::CHANNEL_GROCERY_OBJECT;
        }
        $hasBeenAuthenticated = ! is_null($user) ? Slide::TARGET_LOGGED_IN : Slide::TARGET_GUEST;
        $slides = Slide::where('region_id', $regionId)
                       ->where('city_id', $cityId)
                       ->whereIn('channel', [$slideChannel, Slide::TYPE_FOOD_AND_GROCERY_OBJECT])
                       ->whereIn('has_been_authenticated', [$hasBeenAuthenticated, Slide::TARGET_EVERYONE])
                       ->where('expires_at', '>', now())
                       ->where('begins_at', '<', now())
                       ->get();

        $slides = SlideResource::collection($slides);

        $cart = null;

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $sharedResponse = [
            'cart' => null,
            'slides' => $slides,
            'estimated_arrival_time' => [
                'value' => '30-45',
                'unit' => 'min',
            ],
        ];

        if ($channel == config('app.app-channels.grocery')) {
            $response = [
                'branch' => null,
                'distance' => null,
                'hasAvailableBranchesNow' => false,
                'categories' => [],
            ];

            $response['categories'] = cache()->rememberForever('all_grocery_categories_with_products', function () {
                $groceryParentCategories = Taxonomy::active()->groceryCategories()->parents()->get();

                return GroceryCategoryParentResource::collection($groceryParentCategories);
            });


            if ( ! is_null($user) && ! is_null($selectedAddress = $request->input('selected_address_id'))) {
                $selectedAddress = Location::find($selectedAddress);
                if (is_null($selectedAddress)) {
                    return $this->respondNotFound('Address not found!');
                }
                $latitude = $selectedAddress->latitude;
                $longitude = $selectedAddress->longitude;
            }

            [$distance, $branch] = Branch::getClosestAvailableBranch($latitude, $longitude);
            if ( ! is_null($distance)) {
                $response['distance'] = $distance;
            }
            if ( ! is_null($branch)) {
                $response['branch'] = new BranchResource($branch);
                $response['hasAvailableBranchesNow'] = true;

                if ( ! is_null($user)) {
                    $userCart = Cart::retrieve($branch->chain_id, $branch->id, $user->id);
                    $cart = new CartResource($userCart);
                    $sharedResponse['cart'] = $cart;
                    $activeOrders = Order::whereUserId($user->id)
                                         ->whereNotIn('status', [
                                             Order::STATUS_CANCELLED,
                                             Order::STATUS_DELIVERED,
                                         ])
                                         ->whereChainId($branch->chain_id)
                                         ->latest();
                    $activeOrdersCount = $activeOrders->count();
                    $sharedResponse['activeOrders'] = OrderResource::collection($activeOrders->take(4)->get());
                    $sharedResponse['totalActiveOrders'] = $activeOrdersCount;
                }
//            } else {
                // It's too late no branch is open for now, so sorry
                // No Branch
                // No Cart
            }

            // Always in grocery the EA is 20-30, for dynamic values use "->distance" attribute from above.
            $sharedResponse['estimated_arrival_time']['value'] = '20-30';

        } else {
            $response['categories'] = cache()->rememberForever('all_food_categories_with_products', function () {
                $categories = Taxonomy::active()->foodCategories()->get();

                return FoodCategoryResource::collection($categories);
            });

            $branches = Branch::active()->whereType(Branch::CHANNEL_FOOD_OBJECT)->latest()->get();
            $response['branches'] = FoodBranchResource::collection($branches);
        }

        return $this->respond(array_merge($sharedResponse, $response));
    }
}
