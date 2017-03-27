<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
class SiteController extends Controller {

    public function __construct() {        
    }

    public function index() {
    }

    public function change_language() {
        $msg = array(
            'code' => 0,
            'message' => 'success'
        );
        $selectedLocale = Input::get('locale');
        if(!array_key_exists($selectedLocale, config('app.locales'))) {
            $selectedLocale = config('app.fallback_locale');
        }
        Session::put('locale', $selectedLocale);
        return response()->json($msg);
    }

    public function reloadCaptcha() {
        return response()->json([
            'url' => captcha_src(),
        ]);
    }
}
