<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Validator;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:profile-update', ['except' => []]);
    }

    public function index()
    {
        $user = Auth::user();
        return view('site.profile', array(
            'user' => $user,
        ));
    }

    public function update(Request $request)
    {

        $user = Auth::user();

        $passwordRules = 'required|min:8|max:64|regex:/^(?=.*[a-zA-Z])(?=.*\d).{8,64}$/|confirmed';
        if (Input::get('password') == '') {
            $passwordRules = 'confirmed';
        }

        $validator = Validator::make($request->all(), [
            'fullname' => 'required|min:4|max:255',
            'password' => $passwordRules,
            'email' => "required|email|unique:users,email,$user->id",
            'phone' => "required|digits_between:7,16|unique:users,phone,$user->id",
            'birthday' => 'required|date_format:Y-m-d|before:-13 years',

        ], ['before' => trans('validation.smallest_age', ['age' => 13])]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput(
                    $request->except('password')
                )->withErrors($validator);
        } else {
            $user->fullname = Input::get('fullname');
            if (Input::get('password') != '') {
                $user->password = bcrypt(Input::get('password'));
            }
            $user->email = Input::get('email');
            $user->birthday = Input::get('birthday');
            $user->phone = Input::get('phone');

            $user->save();

            return redirect()->back()->with('message', trans('general.update_successfully'));
        }
    }

}
