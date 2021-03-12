<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\LocationResource;
use App\Http\Resources\TaxonomyResource;
use App\Models\Taxonomy;

class HomeController extends BaseApiController
{
    public function boot()
    {
        return $this->respond([
            'force-update' => 'disabled', // soft,hard,disabled
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
            'estimated_arrival_time' => "30-45",
        ];

        if ($channel == config('app.app-channels.grocery')) {
            $groceryParentCategories = Taxonomy::groceries()->root()->get();
            $categories = TaxonomyResource::collection($groceryParentCategories);

            // Todo: it should be based on which branch the user has selected
            $sharedResponse['estimated_arrival_time'] = "10-20";
            $response = [
                'categories' => $categories,
            ];
        } else {
            $response = [];
        }

        return $this->respond(array_merge($sharedResponse, $response));
    }
}
