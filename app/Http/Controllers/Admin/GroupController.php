<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Group;

class GroupController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('permission:group-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:group-edit', ['only' => ['update', 'edit']]);
        $this->middleware('permission:group-list', ['only' => ['listing']]);
        $this->middleware('permission:group-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
        return view('admin.groups.index');
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
        $order_by = $columns[$num]['data'];
        $order_type = $order[0]['dir'];

        $search = Input::get('search');
        $keyword = $search['value'];

        $total = Group::count();

        $total_filter = Group::where('name', 'LIKE', "%$keyword%")
                ->orWhere('display_name', 'LIKE', "%$keyword%")
                ->orWhere('description', 'LIKE', "%$keyword%")
                ->count();

        $groups = Group::where('name', 'LIKE', "%$keyword%")
                ->orWhere('display_name', 'LIKE', "%$keyword%")
                ->orWhere('description', 'LIKE', "%$keyword%")
                ->orderBy($order_by, $order_type)
                ->skip($start)
                ->take($length)
                ->get();


        $arr = array(
            'recordsTotal' => $total,
            'data' => $groups,
            'draw' => $draw,
            'recordsFiltered' => $total_filter
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
                    'name' => 'required|max:48|unique:groups,name',
                    'display_name' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/groups')
                            ->withErrors($validator);
        } else {
            $group = new Group();
            $group->name = $request->input('name');
            $group->display_name = $request->input('display_name');
            $group->description = $request->input('description');

            $group->save();

            // redirect
            Session::flash('message', trans('notification.Add group successfully'));
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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $group = Group::findOrFail($id);
        return view('admin.groups.edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $group = Group::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|max:48|unique:groups,name,' . $group->id,
            'display_name' => 'required',
        ]);

        $group->name = Input::get('name');
        $group->display_name = Input::get('display_name');
        $group->description = Input::get('description');
        $group->save();

        Session::flash('message', trans('notification.Update group successfully!'));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $group = Group::findOrFail($id);

        $arr = array(
            'code' => 1,
            'message' => 'error'
        );

        if ($group->delete()) {
            $arr = array(
                'code' => 0,
                'message' => 'success'
            );
        }

        return response()->json($arr);
    }

}
