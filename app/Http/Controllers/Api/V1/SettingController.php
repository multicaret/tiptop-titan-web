<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\PreferenceResource;
use App\Models\Preference;
use Illuminate\Http\Request;

class SettingController extends BaseApiController
{

    /**
     * @param  Request  $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return PreferenceResource::collection(Preference::getAllPluckValueKey());
    }


    /**
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfileSettings(Request $request)
    {
        $user = auth()->user();
        if ($request->has('settings') && $request->input('settings')) {
            $user->settings = json_decode($request->settings);
            $user->save();
        }

        return $this->respondWithMessage(__('Updated successfully'));
    }

    /**
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function defaults(Request $request)
    {
        $defaultConfigs = config('defaults');
        foreach ($defaultConfigs['images'] as $index => $defaultConfig) {
            $defaultConfigs['images'][$index] = url($defaultConfig);
        }

        return response([
            'defaults' => $defaultConfigs,
        ]);
    }

    public function locales()
    {
        $locales = localization()->getSupportedLocales();

        return $locales->flatten();
    }
}
