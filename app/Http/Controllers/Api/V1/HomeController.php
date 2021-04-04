<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\BootResource;
use App\Http\Resources\BranchResource;
use App\Http\Resources\CartResource;
use App\Http\Resources\GroceryCategoryParentResource;
use App\Http\Resources\SlideResource;
use App\Models\Boot;
use App\Models\Branch;
use App\Models\Cart;
use App\Models\Location;
use App\Models\Order;
use App\Models\Slide;
use App\Models\Taxonomy;
use Illuminate\Http\Request;

class HomeController extends BaseApiController
{
    public function boot(Request $request): \Illuminate\Http\JsonResponse
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
        // Todo: retrieve slides based on channel.
        $slides = SlideResource::collection(Slide::all());
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
                    $sharedResponse['activeOrders'] = Order::whereUserId($user->id)
                                                           ->whereNotIn('status', [
                                                               Order::STATUS_CANCELLED,
                                                               Order::STATUS_DELIVERED,
                                                           ])
                                                           ->whereChainId($branch->chain_id)
                                                           ->get();
                }
//            } else {
                // It's too late no branch is open for now, so sorry
                // No Branch
                // No Cart
            }

            // Always in grocery the EA is 20-30, for dynamic values use "->distance" attribute from above.
            $sharedResponse['estimated_arrival_time']['value'] = '20-30';

        } else {
            $response = [];
        }

        return $this->respond(array_merge($sharedResponse, $response));
    }
}
