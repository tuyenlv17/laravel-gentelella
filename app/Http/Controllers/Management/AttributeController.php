<?php

namespace App\Http\Controllers\Management;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Attribute;

class AttributeController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('permission:management-attribute-crud', ['except' => []]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('management.attributes', array(
            'action' => 'add',
        ));
    }

    /**
     * List data of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing() {
        $start = Input::get('start');
        $length = Input::get('length');
        $draw = Input::get('draw');

        $order = Input::get('order');
        $columns = Input::get('columns');

        $num = $order[0]['column'];
        $orderBy = $columns[$num]['data'];
        $orderType = $order[0]['dir'];

        $search = Input::get('search');
        $keyword = $search['value'];

        $total = Attribute::count();

        $totalFilter = Attribute::
                where(function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', "%$keyword%");
                })
                ->count();

        $attributes = Attribute::
                where(function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', "%$keyword%");
                })
                ->orderBy($orderBy, $orderType)
                ->skip($start)
                ->take($length)
                ->get();


        $arr = array(
            'recordsTotal' => $total,
            'data' => $attributes,
            'draw' => $draw,
            'recordsFiltered' => $totalFilter
        );

        return response()->json($arr);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
                    'name' => 'required|max:32|unique:attributes,name',
        ]);

        if ($validator->fails()) {
            return redirect('management/attributes')
                            ->withErrors($validator);
        } else {
            $attribute = new Attribute();
            $attribute->name = Input::get('name');
//            $attribute->display_name = Input::get('display_name');
            $attribute->description = Input::get('description');

            $attribute->save();

            return redirect()->back()->with('message', trans('general.add_successfully'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $attribute = Attribute::findOrFail($id);
        return view('management.attributes', array(
            'action' => 'edit',
            'attribute'=> $attribute,
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $attribute = Attribute::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|max:32|unique:attributes,name,' . $attribute->id,
//            'display_name' => 'required',
        ]);

        $attribute->name = Input::get('name');
//        $attribute->display_name = Input::get('display_name');
        $attribute->description = Input::get('description');
        $attribute->save();

        return redirect()->back()->with('message', trans('general.update_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $attribute = Attribute::findOrFail($id);

        $arr = array(
            'code' => 1,
            'message' => 'error'
        );

        if ($attribute->delete()) {
            $arr = array(
                'code' => 0,
                'message' => 'success'
            );
        }

        return response()->json($arr);
    }

}
