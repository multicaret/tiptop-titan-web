<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\PreferenceResource;
use App\Models\Preference;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SettingController extends BaseApiController
{

    /**
     * @param  Request  $request
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return PreferenceResource::collection(Preference::getAllPluckValueKey());
    }


    /**
     * @param  Request  $request
     *
     * @return JsonResponse
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
     * @return JsonResponse
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

    /**
     * @param  Request  $request
     *
     * @return JsonResponse
     */
    public function support(Request $request)
    {
        return $this->respond([
            'supportNumber' => Preference::retrieveValue('support_number'),
        ]);
    }

    public function locales()
    {
        $locales = localization()->getSupportedLocales();

        return $locales->flatten();
    }
}
