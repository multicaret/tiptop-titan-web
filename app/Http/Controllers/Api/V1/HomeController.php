<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\LocationResource;
use App\Http\Resources\TaxonomyResource;
use App\Models\Taxonomy;
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
        $user = auth()->user();
        $slides = $addresses = [];

        $usersLatitude = $request->latitude;
        $usersLongitude = $request->latitude;

        if ( ! is_null($user)) {
            $addresses = LocationResource::collection($user->addresses());
            $user->latitude = $usersLatitude;
            $user->longitude = $usersLongitude;
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
            $groceryParentCategories = Taxonomy::groceries()->root()->get();
            $categories = TaxonomyResource::collection($groceryParentCategories);

            if ( ! is_null($user) && ! is_null($selectedAddress = $request->input('selected_address'))) {
                // here is the currently selected address: $selectedAddress
                $selectedBranch = Branch::first();
                $branch = new BranchResource($selectedBranch);
            }
            // Always in grocery the EA is 20-30
            $sharedResponse['estimated_arrival_time']['value'] = '20-30';
            $response = [
                'categories' => $categories,
                'branch' => $branch,
            ];
        } else {
            $response = [];
        }

        return $this->respond(array_merge($sharedResponse, $response));
    }
}
