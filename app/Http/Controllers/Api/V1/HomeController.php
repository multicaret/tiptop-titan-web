<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\GroceryCategoryParentIndexResource;
use App\Http\Resources\LocationResource;
use App\Models\Branch;
use App\Models\Taxonomy;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends BaseApiController
{
    public function boot(Request $request)
    {
        $forceUpdateMethod = 'disabled';
        // Example
        if ($request->input('build_number') < 25) {
            $forceUpdateMethod = 'hard';
        }

        return $this->respond([
            'force-update' => $forceUpdateMethod, // soft,hard,disabled
            'dialog' => [
                'title' => 'Dialog Title Bro',
                'description' => 'This is a test example of a dialog message body',
            ],
            'config' => [
                'foo' => 'bar'
            ],
        ]);
    }

    public function root()
    {
        return $this->respondWithMessage('Welcome to '.config('app.name'));
    }

    public function index(Request $request)
    {
        $channel = strtolower($request->input('channel'));
        $user = /*auth()->user()*/
            User::first();
        $response = $slides = $addresses = [];

        $userLatitude = $request->latitude;
        $userLongitude = $request->longitude;

        if ( ! is_null($user)) {
            $addresses = LocationResource::collection($user->addresses);
            $user->latitude = $userLatitude;
            $user->longitude = $userLongitude;
            $user->save();
        }

        $sharedResponse = [
            'addresses' => $addresses,
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

            $groceryParentCategories = Taxonomy::published()->groceryCategories()->parents()->get();
            $response['categories'] = GroceryCategoryParentIndexResource::collection($groceryParentCategories);

            if ( ! is_null($user) && ! is_null($selectedAddress = $request->input('selected_address_id'))) {
                /*$selectedAddress = Location::find($selectedAddress);
                $selectedAddress->latitude;
                $selectedAddress->longitude;*/
                [$distance, $branch] = Branch::getClosestAvailableBranch($request->latitude, $request->longitude);
                if ( ! is_null($distance)) {
                    $response['distance'] = $distance;
                }
                if ( ! is_null($distance)) {
                    $response['branch'] = $branch;
                    $response['hasAvailableBranchesNow'] = true;
                }
            }
            // Always in grocery the EA is 20-30, for dynamic values use "->distance" attribute from above.
            $sharedResponse['estimated_arrival_time']['value'] = '20-30';

        } else {
            $response = [];
        }

        return $this->respond(array_merge($sharedResponse, $response));
    }
}
