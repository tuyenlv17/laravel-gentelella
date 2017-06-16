<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Group;

class DictController extends Controller
{

    public function __construct()
    {

    }

    /**
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $word = Input::get('word');
        $dictData = [
            'old'=> [
                'uk_pron' => 'balba',
            ],
        ];
        return response()->json($dictData);
    }


}
