<?php

namespace App\Http\Controllers\Admin\RBAC;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Role;
use App\Permission;
use App\Group;
use DB;

class RoleController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('permission:rbac-role-crud', ['except' => []]);
    }

    public function index() {
        $permissions = DB::table('permissions')                         
                         ->select('permissions.*', 'groups.display_name as group_name')
                         ->rightJoin('groups', 'groups.id', '=', 'permissions.group_id')
                         ->orderBy('group_id', 'asc')
                         ->get();
        $permissionGroup = array();
        foreach ($permissions as $permission) {
            $permissionGroup[$permission->group_name][] = $permission;
        }
        
        return view('admin.rbac.roles', array(
            'action' => 'add',
            'permissionGroup' => $permissionGroup,
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

        $total = Role::count();

        $totalFilter = Role::where('name', 'LIKE', "%$keyword%")
                ->orWhere('display_name', 'LIKE', "%$keyword%")
                ->orWhere('description', 'LIKE', "%$keyword%")
                ->orWhere('default_url', 'LIKE', "%$keyword%")
                ->count();

        $roles = Role::where('name', 'LIKE', "%$keyword%")
                ->orWhere('display_name', 'LIKE', "%$keyword%")
                ->orWhere('description', 'LIKE', "%$keyword%")
                ->orWhere('default_url', 'LIKE', "%$keyword%")
                ->orderBy($orderBy, $orderType)
                ->skip($start)
                ->take($length)
                ->get();

        $arr = array(
            'recordsTotal' => $total,
            'data' => $roles,
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
                    'name' => 'required|max:64|unique:roles,name',
                    'display_name' => 'required',
                    'default_url' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/roles')
                            ->withErrors($validator);
        } else {
            $role = new Role();
            $role->name = Input::get('name');
            $role->display_name = Input::get('display_name');
            $role->description = Input::get('description');
            $role->default_url = Input::get('default_url');
            $role->save();
            
            $permission = Input::get('permission');
             if (count($permission) > 0) {
                foreach (Input::get('permission') as $key => $value) {
                    $role->attachPermission($value);
                }
            }
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
        $role = Role::findOrFail($id);       

        $currentPermisisons = DB::table("permission_role")
                                 ->where("permission_role.role_id", $id)
                                 ->pluck('permission_role.permission_id')
                                 ->toArray();   
        
        $permissions = DB::table('permissions')                         
                         ->select('permissions.*', 'groups.display_name as group_name')
                         ->rightJoin('groups', 'groups.id', '=', 'permissions.group_id')
                         ->orderBy('group_id', 'asc')
                         ->get();
        $permissionGroup = array();
        foreach ($permissions as $permission) {
            $permissionGroup[$permission->group_name][] = $permission;
        }
        
        return view('admin.rbac.roles', array(
            'action' => 'edit',
            'role' => $role,
            'currentPermisisons' => $currentPermisisons,
            'permissionGroup' => $permissionGroup,
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
        $role = Role::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|max:64|unique:roles,name,' . $role->id,
            'display_name' => 'required',
        ]);

        $role->name = Input::get('name');
        $role->display_name = Input::get('display_name');
        $role->description = Input::get('description');
        $role->default_url = Input::get('default_url');
        $role->save();

        DB::table("permission_role")->where("permission_role.role_id", $id)
                ->delete();

        $permission = Input::get('permission');

        if (count($permission) > 0) {
            foreach ($permission as $key => $value) {
                $role->attachPermission($value);
            }
        }

        return redirect()->back()->with('message', trans('general.update_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $role = Role::findOrFail($id);

        $arr = array(
            'code' => 1,
            'message' => 'error'
        );

        if (DB::table("roles")->where('id', $id)->delete()) {
            $arr = array(
                'code' => 0,
                'message' => 'success'
            );

//            DB::table("permission_role")->where("permission_role.role_id", $id)
//                    ->delete();
        }

        return response()->json($arr);
    }

}
