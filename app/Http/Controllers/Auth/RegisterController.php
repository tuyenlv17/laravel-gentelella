<?php

namespace App\Http\Controllers\Auth;

use DB;
use App\User;
use App\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|min:4|max:32|regex:/^[a-zA-Z0-9_]{4,32}$/|unique:users,username',
            'fullname' => 'required|min:4|max:255',
            'password' => 'required|min:8|max:64|regex:/^(?=.*[a-zA-Z])(?=.*\d).{8,64}$/|confirmed',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|digits_between:7,16|unique:users,phone',
            'birthday' => 'required|date_format:Y-m-d',
            'captcha' => 'required|captcha',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {

        $user = new User();
        $user->username = $data['username'];
        $user->fullname = $data['fullname'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        $user->birthday = $data['birthday'];
        $user->password = bcrypt($data['password']);

        DB::transaction(function () use ($user) {
            $user->save();
            $roles = Role::whereIn('name', ['guest'])
                ->get();
            $user->attachRoles($roles);
        });

        return $user;
    }

    public function showRegistrationForm()
    {
        return view('auth.register', [
            'register' => TRUE,
        ]);
    }

}
