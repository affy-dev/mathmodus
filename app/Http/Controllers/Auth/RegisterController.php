<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

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
    protected $redirectTo = '/payment';

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
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'  => ['required', 'string', 'min:8'],
            'username'  => ['required', 'string'],
            'roles'     => ['required']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $inputData = [
            'name'      => $data['name'],
            'email'     => $data['email'],
            'username'  => $data['username'],
            'password'  => Hash::make($data['password']),
        ];
        $user = User::create($inputData);
        $user->roles()->sync($data['roles']);
        return $user;
    }

    protected function showRegistrationForm() {
        $roles = Role::where('show_on_register_page', 1)->pluck('title', 'id');
        return view('auth.register', compact('roles'));
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = $this->create($request->all());
        event(new Registered($user));
        session(['startPayment' => true, 'userId' => $user->id]);
        // Alert::success('Registration process got successfull. Please wait for the admin to activate your account', '');
        return $this->registered($request, $user)
                        ?: redirect()->route('payment');
    }
}
