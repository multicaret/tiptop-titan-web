<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\UserResource;
use App\Models\Language;
use App\Models\Preference;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
            $accessToken = $user->createToken(strtolower(config('app.name')))->accessToken;

            return $this->respond([
                'user' => new UserResource($user),
                'accessToken' => $accessToken,
                'isEmailVerified' => self::isEmailVerified($user),
                'isApproved' => self::isApproved($user),
                'isSuspended' => self::isSuspended($user),
            ]);
        } else {
            return $this->respondWithMessage(trans('auth.failed'));
        }
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  Request  $request
     *
     * @return Json
     * @throws \Exception
     */
    public function register(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|min:3|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'phone_number' => [
                'required',
                Rule::unique('users')->where(function ($query) use ($request) {
                    return $query
                        ->where('phone_number', $request->phone_number)
                        ->where('phone_country_code', $request->phone_country_code);
                }),
            ]
        ]);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        \DB::beginTransaction();
        $language = cache()->tags('languages')->rememberForever('all_languages', function () {
            return Language::all();
        })->where('code', $request->input('locale', config('defaults.language.code')))->first();

        $user = new User;
        $user->language_id = ! is_null($language) ? $language->id : config('defaults.language.id');
        $user->country_id = config('defaults.country.id');
        $user->currency_id = config('defaults.currency.id');
        list($user->first, $user->last) = User::extractFirstAndLastNames($request->input('name'));
        $user->password = bcrypt($request->input('password'));
//        todo: Check if the username should have random numbers like that
        $user->username = strstr($request->input('email'), '@', 1).rand(1000, 9999);
        $user->email = $request->input('email');
        $user->phone_number = $request->input('phone_number');
        $user->phone_country_code = $request->input('phone_country_code');
        $user->dob = Carbon::parse($request->input('dob'));
        $user->gender = User::determineGender($request->input('gender'));
        // approving immediately as the default settings
        $user->approved_at = now();

        $role = $request->input('role');
        if (is_null($role) || in_array($role, [User::ROLE_SUPER, User::ROLE_ADMIN])) {
            $role = User::ROLE_USER;
        }
        $user->assignRole($role);
        if ($request->has('mobile_app')) {
            $user->mobile_app = json_decode($request->mobile_app);
        }
        $user->save();

        \DB::commit();

        if ( ! empty($user)) {
            $accessToken = $user->createToken(config('app.name'))->accessToken;
            event(new Registered($user));

            return $this->respond([
                'user' => new UserResource($user),
                'accessToken' => $accessToken,
                'isEmailVerified' => self::isEmailVerified($user),
                'isApproved' => self::isApproved($user),
                'isSuspended' => self::isSuspended($user),
            ]);
        } else {
            return $this->respondWithMessage(__('It seems like there is an error, please try again later!'));
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
        if ($user->token()->delete()) {
            return $this->respondWithMessage(trans('auth.successfully_logged_out'));
        }

        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                    ->respondWithMessage(__('It seems like there is an error, please try again later!'));
    }

    /**
     * @param  Request  $request
     * @param         $userId
     *
     * @return array
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\InvalidBase64Data
     */
    public function profile(Request $request)
    {
        $user = auth()->user();
        // authenticated but not found? is this even possible dear Laravel?
        if (is_null($user)) {
            return $this->respondNotFound();
        }

        $rules = [
            'name' => 'required',
            'email' => 'required|email|min:3|max:255|unique:users,email,'.$user->id,
        ];

        $validator = validator()->make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        \DB::beginTransaction();
        $language = cache()->tags('languages')->rememberForever('all_languages', function () {
            return Language::all();
        })->where('code', $request->input('locale', config('defaults.language.code')))->first();
        $user->language_id = ! is_null($language) ? $language->id : config('defaults.language.id');

        list($user->first, $user->last) = User::extractFirstAndLastNames($request->input('name'));
        $user->email = $request->input('email');
        $user->phone_number = $request->input('phone_number');
        $user->phone_country_code = $request->input('phone_country_code');
        $user->dob = Carbon::parse($request->input('dob'));
        $user->gender = User::determineGender($request->input('gender'));
        if ($request->has('mobile_app') && $request->input('mobile_app')) {
            $user->mobile_app = json_decode($request->mobile_app);
        }
        if ($request->has('settings') && $request->input('settings')) {
            $user->settings = json_decode($request->settings);
        }
        $user->save();

        if ( ! empty($avatar = $request->input('avatar'))
            && Str::contains($avatar, 'base64,')) {
            $user->addMediaFromBase64($avatar)->toMediaCollection('avatar');
        }
        \DB::commit();

        return $this->respond([
            'user' => new UserResource($user),
        ], null, trans('strings.successfully_updated'));
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
