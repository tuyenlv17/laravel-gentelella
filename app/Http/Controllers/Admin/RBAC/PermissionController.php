<?php

namespace App\Http\Controllers\Admin\RBAC;

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
        $this->middleware('permission:rbac-permission-crud', ['except' => []]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $groups = Group::pluck('display_name', 'id')->toArray();
        return view('admin.rbac.permissions', array(
            'action' => 'add',
            'groups' => $groups,
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

        $total = Permission::count();

        $totalFilter = DB::table('permissions')
                ->join('groups', 'groups.id', '=', 'group_id')
                ->where(function ($query) use ($keyword) {
                    $query->where('permissions.name', 'LIKE', "%$keyword%")
                    ->orWhere('permissions.display_name', 'LIKE', "%$keyword%")
                    ->orWhere('permissions.description', 'LIKE', "%$keyword%")
                    ->orWhere('groups.display_name', 'LIKE', "%$keyword%");
                })
                ->count();

        $permissions = DB::table('permissions')
                ->select('permissions.*', DB::raw('groups.display_name as group_name'))
                ->join('groups', 'groups.id', '=', 'group_id')
                ->where(function ($query) use ($keyword) {
                    $query->where('permissions.name', 'LIKE', "%$keyword%")
                    ->orWhere('permissions.display_name', 'LIKE', "%$keyword%")
                    ->orWhere('permissions.description', 'LIKE', "%$keyword%")
                    ->orWhere('groups.display_name', 'LIKE', "%$keyword%");
                })
                ->orderBy($orderBy, $orderType)
                ->skip($start)
                ->take($length)
                ->get();


        $arr = array(
            'recordsTotal' => $total,
            'data' => $permissions,
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
                    'name' => 'required|max:64|unique:permissions,name',
                    'display_name' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/permissions')
                            ->withErrors($validator);
        } else {
            $groupId = DB::table('groups')->where('id', '=', Input::get('group'))->value('id');
            if (!isset($groupId)) {
                $groupId = DB::table('groups')->where('name', '=', 'other')->value('id');
            }
            $permission = new Permission();
            $permission->name = Input::get('name');
            $permission->display_name = Input::get('display_name');
            $permission->description = Input::get('description');
            $permission->group_id = $groupId;
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
        $groups = Group::pluck('display_name', 'id')->toArray();
        return view('admin.rbac.permissions', array(
            'action' => 'edit',
            'permission' => $permission,
            'groups' => $groups
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
        $groupId = DB::table('groups')->where('id', '=', Input::get('group'))->value('id');
        if (!isset($groupId)) {
            $groupId = DB::table('groups')->where('name', '=', 'other')->value('id');
        }
        $permission->name = Input::get('name');
        $permission->display_name = Input::get('display_name');
        $permission->description = Input::get('description');
        $permission->group_id = $groupId;
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

//            DB::table("role_permission")->where("role_permission.permission_id", $id)
//                    ->delete();
        }

        return response()->json($arr);
    }

}
