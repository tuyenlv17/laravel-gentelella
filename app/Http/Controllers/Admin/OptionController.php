<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Option;

class OptionController extends Controller {

    function __construct() {
        $this->middleware('auth');
        $this->middleware('permission:option-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:option-edit', ['only' => ['update', 'edit']]);
        $this->middleware('permission:option-list', ['only' => ['listing']]);
        $this->middleware('permission:option-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('admin.options.index');
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
        $order_by = $columns[$num]['data'];
        $order_type = $order[0]['dir'];

        $search = Input::get('search');
        $keyword = $search['value'];

        $total = Option::count();

        $total_filter = Option::where('key', 'LIKE', "%$keyword%")
                ->orWhere('value', 'LIKE', "%$keyword%")
                ->count();

        $options = Option::where('key', 'LIKE', "%$keyword%")
                ->orWhere('value', 'LIKE', "%$keyword%")
                ->orderBy($order_by, $order_type)
                ->skip($start)
                ->take($length)
                ->get();

        $arr = array(
            'recordsTotal' => $total,
            'data' => $options,
            'draw' => $draw,
            'recordsFiltered' => $total
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
                    'key' => 'required|unique:options|max:64',
                    'value' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('admin/options')
                            ->withErrors($validator)
                            ->withInput();
        } else {
            // store
            $option = new Option;
            $option->key = Input::get('key');
            $option->value = Input::get('value');
            $option->save();

            // redirect
            Session::flash('message', 'Thêm tham số thành công!');
            return redirect()->back();
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
        $option = Option::findOrFail($id);
        return view('admin.options.edit')->withOption($option);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $option = Option::findOrFail($id);

        $this->validate($request, [
            'key' => 'required|max:64|unique:options,key,' . $option->id,
            'value' => 'required',
        ]);

        $input = $request->all();

        $option->fill($input)->save();

        Session::flash('message', 'Cập nhật tham số thành công!');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $option = Option::findOrFail($id);

        $arr = array(
            'code' => 1,
            'message' => 'error'
        );

        if ($option->delete()) {
            $arr = array(
                'code' => 0,
                'message' => 'success'
            );
        }

        return response()->json($arr);
    }

}
