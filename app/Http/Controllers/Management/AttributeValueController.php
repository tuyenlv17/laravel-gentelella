<?php

namespace App\Http\Controllers\Management;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\AttributeValue;
use App\Attribute;
use DB;

class AttributeValueController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('permission:management-attribute-value-crud', ['except' => []]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $attributes = Attribute::pluck('name', 'id')->toArray();
        return view('management.attribute-value', array(
            'action' => 'add',
            'attributes' => $attributes,
        ));
    }

    /**
     * Display a listing of the resource.
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

        $total = AttributeValue::count();
        
        $attribute_id = Input::get('attribute');

        $totalFilter = DB::table('attribute_val')
                ->join('attributes', function($join) use ($attribute_id){
                    $join->on('attributes.id', '=', 'attribute_id')
                            ->where('attributes.id', '=', $attribute_id);
                })
                ->where(function ($query) use ($keyword) {
                    $query->where('attribute_val.name', 'LIKE', "%$keyword%")
                          ->orWhere('attributes.name', 'LIKE', "%$keyword%");;
                })
                ->count();

        $attribute_val = DB::table('attribute_val')
                ->select('attribute_val.*', DB::raw('attributes.name as attribute_name'))
                ->join('attributes', function($join) use ($attribute_id){
                    $join->on('attributes.id', '=', 'attribute_id')                            
                         ->where('attributes.id', '=', $attribute_id);;
                })
                ->where(function ($query) use ($keyword) {
                    $query->where('attribute_val.name', 'LIKE', "%$keyword%")
                          ->orWhere('attributes.name', 'LIKE', "%$keyword%");
                })
                ->orderBy($orderBy, $orderType)
                ->skip($start)
                ->take($length)
                ->get();


        $arr = array(
            'recordsTotal' => $total,
            'data' => $attribute_val,
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
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
                    'name' => 'required|max:32|unique:attribute_val,name',
        ]);

        if ($validator->fails()) {
            return redirect('management/attribute_val')
                            ->withErrors($validator);
        } else {
            $attributeId = DB::table('attributes')->where('id', '=', Input::get('attribute'))->value('id');
            if (!isset($attributeId)) {
                return redirect()->back()->withInput()->withErrors(trans('general.invalid_attribute'));
            }
            $attributeValue = new AttributeValue();
            $attributeValue->name = Input::get('name');
            $attributeValue->attribute_id = $attributeId;
            $attributeValue->save();

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $attributeValue = AttributeValue::findOrFail($id);
        $attributes = Attribute::pluck('name', 'id')->toArray();
        return view('management.attribute-value', array(
            'action' => 'edit',
            'attributeValue' => $attributeValue,
            'attributes' => $attributes
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
        $attributeValue = AttributeValue::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|max:32|unique:attribute_val,name,' . $attributeValue->id,
        ]);
        $attributeId = DB::table('attributes')->where('id', '=', Input::get('attribute'))->value('id');
        if (!isset($attributeId)) {
            return redirect()->back()->withInput()->withErrors(trans('general.invalid_attribute'));
        }
        $attributeValue->name = Input::get('name');
        $attributeValue->attribute_id = $attributeId;
        $attributeValue->save();

        return redirect()->back()->with('message', trans('general.update_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $attributeValue = AttributeValue::findOrFail($id);

        $arr = array(
            'code' => 1,
            'message' => 'error'
        );

        if (DB::table("attribute_val")->where('id', $id)->delete()) {
            $arr = array(
                'code' => 0,
                'message' => 'success'
            );
        }

        return response()->json($arr);
    }

}
