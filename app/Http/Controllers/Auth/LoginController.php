<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Debugbar;
use Cache;

class LoginController extends Controller {
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
    protected $redirectTo = '/site/profile';
    protected $maxAttemps = 5;
    protected $lockoutMinutes = 10;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function username() {
        return 'username';
    }

    protected function hasTooManyLoginAttempts(Request $request) {
        return $this->limiter()->tooManyAttempts(
                        $this->throttleKey($request), $this->maxAttemps, $this->lockoutMinutes
        );
    }

    public function login(Request $request) {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {

            $this->fireLockoutEvent($request);

            if (Cache::has('captcha_login')) {
                $this->validate($request, [
                    'captcha' => 'required|captcha',
                ]);
            } else {
                Cache::put('captcha_login', true, $this->lockoutMinutes);
                return redirect()->back()
                            ->withInput($request->only($this->username(), 'remember'))
                            ->withErrors(['captcha_login' => trans('general.captcha_login')]);
            }
        }
                
        if ($this->attemptLogin($request)) {
            Cache::forget('captcha_login');
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

}
