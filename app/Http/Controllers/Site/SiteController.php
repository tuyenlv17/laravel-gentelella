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
        $selected_locale = Input::get('locale');
        if(!array_key_exists($selected_locale, config('app.locales'))) {
            $selected_locale = config('app.fallback_locale');
        }
        Session::set('locale', $selected_locale);
        return response()->json($msg);
    }

}
