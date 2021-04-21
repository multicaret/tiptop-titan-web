<?php

namespace App\Http\Controllers\Api\Restaurants\V1\Auth;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Socialite;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded;
use Symfony\Component\HttpFoundation\Response;

class SocialiteController extends BaseApiController
{
    protected $supportedProviders = [
        'facebook',
        'twitter',
        'google'
    ];

    /**
     * Obtain the user information from Provider.
     *
     * @param         $provider
     * @param  Request  $request
     *
     * @return RedirectResponse|Redirector
     * @throws FileCannotBeAdded
     */
    public function handleProvider($provider, Request $request)
    {
        if ( ! in_array($provider, $this->supportedProviders)) {
            return abort(Response::HTTP_NOT_FOUND);
        }

        $validationRules = [
            'id' => 'required',
            'token' => 'required',
        ];

        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        if (empty($request->email)) {
            try {
                if ( ! empty($request->secret)) {
                    $socialite = Socialite::driver($provider)
                                          ->userFromTokenAndSecret($request->token, $request->secret);
                } else {
                    $socialite = Socialite::driver($provider)
                                          ->userFromToken($request->token);
                }
            } catch (Exception $e) {
                return $this->respondWithError($e->getMessage());
            }

            $request->request->add([
                'email' => $socialite->getEmail(),
                'name' => $socialite->getName(),
                'avatar' => $socialite->getAvatar()
            ]);
        }

        $user = User::where("providers->{$provider}->id", $request->id)
                    ->orWhere('email', $request->email)
                    ->first();


        if ( ! $user) {
            $username = explode('@', $request->email);
            if (User::where('username', $username[0])->first()) {
                $username = $username[0].'_'.substr($request->id, -4);
            } else {
                $username = $username[0];
            }

            $providers = [
                $provider => [
                    'id' => $request->id,
                    'token' => $request->token,
                    'secret' => $request->secret,
                ]
            ];

            $user = new User();
            // todo: create user

            if ($user) {
                if ( ! empty($request->avatar)) {
                    $user->addMediaFromUrl($request->avatar)->toMediaCollection('avatar');
                }
            }
        }

        $providers = $user->providers;
        $providers[$provider] = [
            'id' => $request->id,
            'token' => $request->token,
            'secret' => $request->secret,
        ];
        $user->providers = $providers;
        $user->status = User::STATUS_ACTIVE;

        if (empty($user->api_token)) {
            $user->api_token = Str::random(60);
        }

        $user->save();

        return $this->respond([
            'user' => new UserResource($user),
        ]);
    }
}
