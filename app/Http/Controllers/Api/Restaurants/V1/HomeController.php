<?php

namespace App\Http\Controllers\Api\Restaurants\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\RemoteConfigResource;
use App\Models\RemoteConfig;
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
}
