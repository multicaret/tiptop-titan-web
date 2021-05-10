<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return Response
     */
    public function showLoginForm()
    {
        session(['link' => url()->previous()]);

        return view('admin.login');
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  Request  $request
     *
     * @return array
     */
    protected function credentials(Request $request)
    {
        $field = filter_var($request->input($this->username()), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([$field => $request->input('login')]);

        return $request->only($field, 'password');
    }

    /**
     * Get the failed login response instance.
     *
     * @param  Request  $request
     *
     * @return RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
                         ->with(['script' => '$("#login-modal").modal("show");', 'errorsIn' => 'login'])
                         ->withInput($request->only($this->username(), 'remember'))
                         ->withErrors([
                             $this->username() => trans('auth.failed'),
                         ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'login';
    }

    /**
     * The user has been authenticated.
     *
     * @param  Request  $request
     * @param  mixed  $user
     *
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $user->timestamps = false;
        $user->last_logged_in_at = now();
        $user->save();
        $user->timestamps = true;

        if ($user->is_admin || $user->is_super) {
            $this->redirectTo = route('admin.index');
        } /*elseif ($user->is_owner) {
            $this->redirectTo = route('dashboard.home');
        } */ else {
            $this->redirectTo = session('link');
        }
    }


    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @param $provider
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

}
