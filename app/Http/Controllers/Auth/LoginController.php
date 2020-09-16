<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

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
    // protected $redirectTo = '/home';
    public function redirectTo(){
        $role = auth()->user()->roles->pluck('id');
        $roleName = $role[0];
        // Check user role
        switch ($roleName) {
            case '1':
                    $rolebasedurl = '/admin';
                    session(['rolebasedurl' => $rolebasedurl]);
                    return $rolebasedurl;
                break;
            case '2':
                    $rolebasedurl = '/admin';
                    session(['rolebasedurl' => $rolebasedurl]);
                    return $rolebasedurl;
                break; 
            case '3':
                    $rolebasedurl = '/admin/exams';
                    session(['rolebasedurl' => $rolebasedurl]);
                    return $rolebasedurl;
                break;
            case '4':
                    $rolebasedurl = '/admin';
                    session(['rolebasedurl' => $rolebasedurl]);
                    return $rolebasedurl;
                break; 
            default:
                    $rolebasedurl = '/login';
                    session(['rolebasedurl' => $rolebasedurl]);
                    return $rolebasedurl;
                break;
        }
    }

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
    * Get the login username to be used by the controller.
    *
    * @return string
    */
    public function username()
    {
        $login = request()->input('identity');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$field => $login]);
        return $field;
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $messages = [
            'identity.required'     => 'Email or username cannot be empty',
            'email.exists'          => 'Email or username already registered',
            'username.exists'       => 'Username is already registered',
            'password.required'     => 'Password cannot be empty',
        ];

        $request->validate([
            'identity'  => 'required|string',
            'password'  => 'required|string',
            'email'     => 'string|exists:users',
            'username'  => 'string|exists:users',
        ], $messages);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // This section is the only change
        if ($this->guard()->validate($this->credentials($request))) {
            $user = $this->guard()->getLastAttempted();

            // Make sure the user is active
            if ($user->user_status == 1 && $this->attemptLogin($request)) {
                // Send the normal successful login response
                return $this->sendLoginResponse($request);
            } else {
                // Increment the failed login attempts and redirect back to the
                // login form with an error message.
                $this->incrementLoginAttempts($request);
                Alert::error('You must be active user to login. Please contact to admin for further information.', '');
                return redirect()
                    ->back()
                    ->withInput($request->only($this->username(), 'remember'));
            }
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

}