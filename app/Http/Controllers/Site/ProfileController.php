<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
class ProfileController extends Controller {

    public function __construct() {        
        $this->middleware('auth');
    }

    public function index() {
    }

    public function change_language() {
        
    }

}
