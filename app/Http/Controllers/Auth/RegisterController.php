<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\Welcome;
use App\Models\Activity;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Region;
use App\Models\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|min:3|max:60',
            'username' => 'required|min:3|max:60',
            'email' => 'required|email|min:3|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'g-recaptcha-response' => 'required|recaptcha',
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {

            return redirect()->back()
                             ->with(['errorsIn' => 'signup'])
                             ->withErrors($validator)
                             ->withInput();
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     *
     * @return User|\Illuminate\Database\Eloquent\Model
     */
    protected function create(array $data)
    {
        list($first, $last) = $this->setFirstAndLastNames($data['name']);
        $email = $data['email'];
        $username = $data['username'];
        $password = $data['password'];
        $user = User::updateOrCreate(['email' => $email],
            $this->setupUserDetails(
                $first,
                $last,
                $username,
                $email,
                config('defaults.social_media_providers'),
                $password
            )
        );
//        \Mail::to($email)->send(new Welcome($user, $password));
        $this->sendEmail($email, Welcome::class, [$user, $password]);
        $this->redirectTo = '/';

        return $user;
    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('frontend.auth.register');
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @param $provider
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialiteUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect(action('Auth\LoginController@redirectToProvider', $provider));
        }

        if ( ! is_null($user = User::where('email', $socialiteUser->getEmail())->first())) {
            $this->createOrUpdateThenAuthenticate($provider, $socialiteUser, $user);

            return redirect(route('dashboard.home'));
        }
        $this->createOrUpdateThenAuthenticate($provider, $socialiteUser);

        return redirect($this->redirectPath())
            ->with('success',
                'تم التسجيل بنجاح، قم بتفعيل حسابك عبر بريدك الإلكتروني كي تستطيع استخدام المنصة!');
    }

    /**
     * @param $avatarUrl
     * @param $user
     */
    private function uploadAvatarPicture($avatarUrl, User $user): void
    {
        if ($avatarUrl) {
            try {
                $user->addMediaFromUrl($avatarUrl)
                     ->toMediaCollection('avatar');
            } catch (FileCannotBeAdded $e) {
                \Log::alert('A user\'s is trying to sign in using a social provider and got no avatar URL! Strange ha!'
                    , ['user_id' => $user->id]);
            }
        }
    }

    /**
     * @param      $first
     * @param      $last
     * @param      $username
     * @param      $email
     * @param      $socialNetworks
     * @param  null  $password
     * @return array
     */
    private function setupUserDetails(
        $first,
        $last,
        $username,
        $email,
        $socialNetworks,
        $password = null
    ): array {

        $currency = Currency::whereCode(geoip()->getLocation()->getAttribute('currency'))->first();
        if (empty($currency)) {
            $currency = Currency::whereCode(config('defaults.currency.code'))->first();
            $this->writeToLog('A Currency was not found in DB :-/ whaaat!');
        }
        $country = Country::whereAlpha2Code(geoip()->getLocation()->getAttribute('iso_code'))->first();
        if (empty($country)) {
            $country = Country::find(config('defaults.country.id'));
            $this->writeToLog('A Country was not found in DB :-/ whaaat!');
        }

        $region = Region::whereCode(geoip()->getLocation()->getAttribute('state'))->first();
        if (empty($region)) {
            /* if the country is empty as well, thus, used the default for it, then use default for region as well!*/
            if (empty($country)) {
                $region = Region::find(config('defaults.region.id'));
            }
            $this->writeToLog('A Region was not found in DB :-/ whaaat!');
        }

        $userArray = [
            'first' => $first,
            'last' => $last,
            'email' => $email,
            'username' => $username,
            'currency_id' => $currency->id,
            'language_id' => Language::whereCode(localization()->getCurrentLocale())->first()->id,
            'country_id' => $country ? $country->id : config('defaults.country.id'),
            'region_id' => $region ? $region->id : config('defaults.region.id'),
            'social_networks' => $socialNetworks,
            'latitude' => geoip()->getLocation()->getAttribute('lat'),
            'longitude' => geoip()->getLocation()->getAttribute('lon'),
        ];

        if ( ! is_null($password)) {
            $userArray['password'] = Hash::make($password);
        }

        if (empty($username)) {
            $userArray['username'] = $this->extractUsernameFromEmail($email);
        }

        return $userArray;
    }

    /**
     * @param $fullName
     *
     * @return array
     */
    private function setFirstAndLastNames($fullName): array
    {
        $first = $fullName;
        $last = null;
        $fullName = explode(' ', $fullName);
        if (count($fullName) > 1) {
            $last = $fullName[count($fullName) - 1];
            unset($fullName[count($fullName) - 1]);
            $first = implode(' ', $fullName);
        }

        return [$first, $last];
    }

    /**
     * @param $email
     *
     * @return string
     */
    public static function extractUsernameFromEmail($email): string
    {
        $username = strstr($email, '@', 1);
        if (User::where('username', $username)->count()) {
            $username = SlugService::createSlug(User::class, 'username', $username);
        }

        return $username;
    }

    private function setSocialProvidersId($providerName, $providerId, $providers = null): array
    {
        if (empty($providers)) {
            $providers = config('app.user_social_providers');
        }
        $providers[$providerName]['id'] = $providerId;

        return $providers;
    }

    /**
     * @param  String  $socialMediaProvider
     * @param  String  $socialiteUser
     * @param  User|null  $user
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function createOrUpdateThenAuthenticate(
        $socialMediaProvider,
        $socialiteUser,
        User $user = null
    ): \Illuminate\Database\Eloquent\Model {
        $isCreating = false;
        $randomPass = null;
        if (empty($user)) {
            $isCreating = true;
            list($first, $last) = $this->setFirstAndLastNames($socialiteUser->getName());
            $email = $socialiteUser->getEmail();
            $username = $socialNetworks = null;
            if ($socialMediaProvider != 'facebook') {
                $username = $socialiteUser->getNickname();
            }
            $randomPass = \Str::random(3).mt_rand(100, 999);
            $password = $randomPass;
        } else {
            $first = $user->first;
            $last = $user->last;
            $username = $user->username;
            $email = $user->email;
            $socialNetworks = $user->social_networks;
            $password = null;
        }
        $user = User::updateOrCreate(['email' => $email],
            $this->setupUserDetails(
                $first, $last, $username, $email,
                $this->setSocialProvidersId($socialMediaProvider, $socialiteUser->getId(), $socialNetworks), $password
            )
        );

        if ( ! is_null($randomPass)) {
            $this->sendEmail($email, Welcome::class, [$user, $randomPass]);
        }

        $this->uploadAvatarPicture($socialiteUser->getAvatar(), $user);
        \Auth::login($user, true);

        if ($isCreating) {
            Activity::create([
                'subject_id' => $user->id,
                'subject_type' => get_class($user),
                'user_id' => $user->id,
                'type' => 'user_created',
                'is_private' => true
            ]);
        }

        return $user;
    }

    /**
     * @param $message
     */
    private function writeToLog($message): void
    {
        \Log::alert($message,
            [
                'geoip' => [
                    'country' => geoip()->getLocation()->getAttribute('country'),
                    'iso_code' => geoip()->getLocation()->getAttribute('iso_code'),
                    'state' => geoip()->getLocation()->getAttribute('state'),
                    'currency' => geoip()->getLocation()->getAttribute('currency'),
                ]
            ]
        );
    }
}
