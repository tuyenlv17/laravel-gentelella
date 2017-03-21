<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use App\User;
use App\Role;
use Validator;

class UserController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
//        $this->middleware('auth');
//        $this->middleware('permission:user-create', [['index', 'store']]);
//        $this->middleware('permission:user-edit', ['only' => ['update', 'edit']]);
//        $this->middleware('permission:user-list', ['only' => ['listing']]);
//        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    public function index() {
        $roles = DB::table('roles')
                ->pluck('display_name', 'id')
                ->toArray();
        return view('admin.users', array(
            'action' => 'add',
            'roles' => $roles
        ));
    }

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
        
        $total = User::count();

        $total_filter = User::where('name', 'LIKE', "%$keyword%")
                ->orWhere('email', 'LIKE', "%$keyword%")
                ->count();

        $users = User::where('name', 'LIKE', "%$keyword%")
                ->orWhere('email', 'LIKE', "%$keyword%")
                ->orderBy($order_by, $order_type)
                ->skip($start)
                ->take($length)
                ->get();


        $arr = array(
            'recordsTotal' => $total,
            'data' => $users,
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
                    'username' => 'required|max:32|unique:users,username',
                    'fullname' => 'required|unique:users,fullname',
                    'password' => 'confirmed'
        ]);

        if ($validator->fails()) {
            return redirect('admin/users')
                            ->withErrors($validator);
        } else {
            $user = new User();
            $user->username = $request->input('username');
            $user->fullname = $request->input('fullname');            
            $user->password = bcrypt(Input::get('password'));
            $user->save();
            
            $role_name = Input::get('role');
            $role = Role::where('name', '=', $role_name)
                ->firstOrFail();
            $user->attachRole($role);

            // redirect
            Session::flash('message', 'Thêm người dùng thành công!');
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
        $user = User::findOrFail($id);
        $roles = Role::all();
        $roles_selection = array();
        foreach ($roles as $role) {
            $roles_selection[$role->name] = $role->display_name;
        }
        $current_role = $user->roles->first()->name;
        return view('admin.users.edit', array(
            'user' => $user,
            'roles' => $roles_selection,
            'current_role' => $current_role
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
        $user = User::findOrFail($id);

        $this->validate($request, [
            'username' => 'required|max:32|unique:users,username,' . $user->id,
            'fullname' => 'required|unique:users,email,' . $user->id,
            'password' => 'confirmed'
        ]);

        $role_name = Input::get('role');
        $role = Role::where('name', '=', $role_name)
                ->firstOrFail();
        $user->name = Input::get('username');
        $user->fullname = Input::get('fullname');
        if (Input::get('password') != '') {
            $user->password = bcrypt(Input::get('password'));
        }
        $user->save();
        $user->detachRoles($user->roles);
        $user->attachRole($role);

        Session::flash('message', 'Cập nhật người dùng thành công!');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $user = User::findOrFail($id);

        $arr = array(
            'code' => 1,
            'message' => 'error'
        );

        if (DB::table("users")->where('id', $id)->delete()) {
            $arr = array(
                'code' => 0,
                'message' => 'success'
            );

            DB::table("role_user")->where("role_user.user_id", $id)
                    ->delete();
        }

        return response()->json($arr);
    }

}
