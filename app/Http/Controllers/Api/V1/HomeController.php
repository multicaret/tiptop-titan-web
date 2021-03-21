<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\BranchResource;
use App\Http\Resources\CartResource;
use App\Http\Resources\GroceryCategoryParentResource;
use App\Http\Resources\LocationResource;
use App\Http\Resources\SlideResource;
use App\Models\Boot;
use App\Models\Branch;
use App\Models\Cart;
use App\Models\Slide;
use App\Models\Taxonomy;
use Illuminate\Http\Request;

class HomeController extends BaseApiController
{
    public function boot(Request $request)
    {
        $forceUpdateMethod = 'disabled';
        $buildNumber = $request->input('build_number');
        $platform = $request->input('platform');

        $bootConfigurations = Boot::where('platform_type', $platform)
                                  ->where('build_number', $buildNumber)
                                  ->first();

        return $this->respond([
            'force-update' => $forceUpdateMethod, // soft,hard,disabled
            'configurations' => $bootConfigurations,
        ]);
    }

    public function root()
    {
        return $this->respondWithMessage('Welcome to '.config('app.name'));
    }

    public function index(Request $request)
    {
        $channel = strtolower($request->input('channel'));
        $user = auth('sanctum')->user();
        $response = $addresses = [];
        $slides = SlideResource::collection(Slide::all());
        $cart = null;

        $latitude = $request->latitude;
        $longitude = $request->longitude;

        if ( ! is_null($user)) {
            $addresses = LocationResource::collection($user->addresses);
            $user->latitude = $latitude;
            $user->longitude = $longitude;
            $user->save();
        }

        $sharedResponse = [
            'addresses' => $addresses,
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
                $groceryParentCategories = Taxonomy::published()->groceryCategories()->parents()->get();

                return GroceryCategoryParentResource::collection($groceryParentCategories);
            });


            [$distance, $branch] = Branch::getClosestAvailableBranch($latitude, $longitude);
            if ( ! is_null($distance)) {
                $response['distance'] = $distance;
            }
            if ( ! is_null($branch)) {
                $response['branch'] = new BranchResource($branch);
                $response['hasAvailableBranchesNow'] = true;

                if ( ! is_null($user) /*&& ! is_null($selectedAddress = $request->input('selected_address_id'))*/) {
                    /*$selectedAddress = Location::find($selectedAddress);
                    $selectedAddress->latitude = $latitude;
                    $selectedAddress->longitude = $longitude;
                    $selectedAddress->save();*/
                    $userCart = Cart::retrieve($branch->chain_id, $branch->id, $user->id);
                    $cart = new CartResource($userCart);
                    $sharedResponse['cart'] = $cart;
                }
            } else {
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
