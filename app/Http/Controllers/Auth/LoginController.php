<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                    return '/admin';
                break;
            case '2':
                    return '/admin';
                break; 
            case '3':
                    return '/admin/exams';
                break;
            case '4':
                    return '/admin';
                break; 
            default:
                    return '/login'; 
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

}
