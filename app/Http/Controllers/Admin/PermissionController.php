<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Permission;
use App\Group;
use DB;

class PermissionController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('permission:permission-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:permission-edit', ['only' => ['update', 'edit']]);
        $this->middleware('permission:permission-list', ['only' => ['listing']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    
    
    public function index() {
        $groups = Group::get();
        $groups_selection = array();
        foreach ($groups as $group) {
            $groups_selection[$group->name] = $group->display_name;
        }
        return view('admin.permissions.index', array(
            'groups' => $groups_selection
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
        $order_by = $columns[$num]['data'];
        $order_type = $order[0]['dir'];

        $search = Input::get('search');
        $keyword = $search['value'];

        $total = Permission::count();

        $total_filter = Permission::where('name', 'LIKE', "%$keyword%")
                ->orWhere('display_name', 'LIKE', "%$keyword%")
                ->orWhere('description', 'LIKE', "%$keyword%")
                ->count();

        $permissions = Permission::where('name', 'LIKE', "%$keyword%")
                ->orWhere('display_name', 'LIKE', "%$keyword%")
                ->orWhere('description', 'LIKE', "%$keyword%")
                ->orderBy($order_by, $order_type)
                ->skip($start)
                ->take($length)
                ->get();       
             

        $arr = array(
            'recordsTotal' => $total,
            'data' => $permissions,
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
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
                    'name' => 'required|max:64|unique:permissions,name',
                    'display_name' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/permissions')
                            ->withErrors($validator);
        } else {
            $permission = new Permission();
            $permission->name = $request->input('name');
            $permission->display_name = $request->input('display_name');
            $permission->description = $request->input('description');

            $permission->save();

            // redirect
            Session::flash('message', 'Thêm quyền người dùng thành công!');
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
        $permission = Permission::findOrFail($id);
        $groups = Group::get();
        $groups_selection = array();
        $current_group = NULL;
        foreach ($groups as $group) {
            $groups_selection[$group->name] = $group->display_name;
            if($group->id == $permission->group_id) {
                $current_group = $group->name;
            }
        }
        return view('admin.permissions.edit', array(
            'permission' => $permission,
            'groups' => $groups_selection
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
        $permission = Permission::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|max:64|unique:permissions,name,' . $permission->id,
            'display_name' => 'required',
        ]);

        $permission->name = Input::get('name');
        $permission->display_name = Input::get('display_name');
        $permission->description = Input::get('description');        
        $group = Group::where('name', Input::get('group'))
                        ->get()
                        ->first();
        
        $permission->group_id = $group->id;
        $permission->save();

        Session::flash('message', 'Cập nhật quyền người dùng thành công!');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $permission = Permission::findOrFail($id);

        $arr = array(
            'code' => 1,
            'message' => 'error'
        );

        if (DB::table("permissions")->where('id', $id)->delete()) {
            $arr = array(
                'code' => 0,
                'message' => 'success'
            );

            DB::table("role_permission")->where("role_permission.permission_id", $id)
                    ->delete();
        }

        return response()->json($arr);
    }

}
