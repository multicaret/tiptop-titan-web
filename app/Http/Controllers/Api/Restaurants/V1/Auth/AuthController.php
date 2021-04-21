<?php

namespace App\Http\Controllers\Api\Restaurants\V1\Auth;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\UserResource;
use App\Models\Preference;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends BaseApiController
{
    /**
     * @param $user
     *
     * @return array
     */
    private static function isApproved($user): array
    {
        return [
            'enabled' => is_null($user->approved_at),
            'message' => Preference::retrieveValue('mobile_app_needs_approval_message', '%name%', $user->name),
        ];
    }

    /**
     * @param $user
     *
     * @return array
     */
    private static function isEmailVerified($user): array
    {
        return [
            'status' => is_null($user->email_verified_at),
            'message' => 'Pleas verify your email first',
        ];
    }

    /**
     * @param $user
     *
     * @return array
     */
    public static function isSuspended($user): array
    {
        return [
            'enabled' => ! is_null($user->suspended_at),
            'message' => Preference::retrieveValue('mobile_app_account_is_suspended', '%name%', $user->name),
        ];
    }

    /**
     * @param  Request  $request
     * @param  array  $additionalRules
     *
     * @return array
     */
    private static function getEssentialValidationRulesOfUser(Request $request, array $additionalRules = []): array
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|min:3|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ];
//        $rules = array_merge($rules, self::phoneRules($request));
        $rules = array_merge($rules, $additionalRules);

        return $rules;
    }

    /**
     * Handle a login request to the application.
     *
     * @param  Request  $request
     *
     * @return json
     */
    public function login(Request $request)
    {
        if ( ! $request->has('email') || ! $request->has('password')) {
            return $this->respondWithMessage(trans('strings.all_fields_are_required'));
        }

        if (auth()->attempt($request->only(['email', 'password']), $request->input('rememberMe'))) {
            $user = auth()->user();
            $user->update([
                'last_logged_in_at' => now()
            ]);
            $deviceName = $request->input('device_name', 'New Device');
            // Todo: to @MK please send the mobile_app_details from mobile side, and send it here, and use it as device name.
            $accessToken = $user->createToken($deviceName,config('defaults.user.mobile_app_details'))->plainTextToken;

            return $this->respond([
                'user' => new UserResource($user),
                'isEmailVerified' => self::isEmailVerified($user),
                'isApproved' => self::isApproved($user),
                'isSuspended' => self::isSuspended($user),
                'accessToken' => $accessToken,
            ]);
        } else {
            return $this->respondWithMessage(trans('auth.failed'));
        }
    }

    /**
     * Handle a logout request for the application.
     *
     * @param  Request  $request
     *
     * @return Json
     */
    public function logout()
    {
        $user = auth()->user();
        $user->update([
            'last_logged_out_at' => now()
        ]);
        if ($user->user()->currentAccessToken()->delete()) {
            return $this->respondWithMessage(trans('auth.successfully_logged_out'));
        }

        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                    ->respondWithMessage(__('It seems like there is an error, please try again later!'));
    }


    /**
     * Get the phone related essential validation rules.
     *
     * @param      $request
     *
     * @return array
     */
    public static function phoneRules($request = null)
    {
        $phoneValidationArray = ['required'];
        if ( ! empty($request)) {
            $phone = $request->phone_number;
            $phoneCode = $request->phone_country_code;
            array_push($phoneValidationArray, Rule::unique('users')->where(function ($query) use ($phoneCode, $phone) {
                return $query->where('phone_country_code', $phoneCode)
                             ->where('phone_number', $phone);
            }));
        }

        return [
            'phone_number' => $phoneValidationArray,
        ];
    }
}
