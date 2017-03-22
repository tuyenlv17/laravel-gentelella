<?php

namespace App\Http\Controllers\Admin\RBAC;

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
        $this->middleware('auth');
        $this->middleware('permission:rbac-user-crud', ['except' => []]);
    }

    public function index() {
        $roles = DB::table('roles')
                ->pluck('display_name', 'id')
                ->toArray();
        return view('admin.rbac.users', array(
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
        $orderBy = $columns[$num]['data'];
        $orderType = $order[0]['dir'];

        $search = Input::get('search');
        $keyword = $search['value'];

        $roles = Input::get('roles');

        $total = User::count();

        $roleSub = DB::table('users')
                ->select(DB::raw("distinct(users.id)"))
                ->leftJoin('role_user', 'user_id', '=', 'users.id')
                ->where(function($query) use ($keyword) {
                    $query->where('username', 'LIKE', "%$keyword%")
                    ->orWhere('fullname', 'LIKE', "%$keyword%");
                })
                ->where(function($query) use ($roles) {
            if (is_array($roles)) {
                $query->whereIn('role_id', $roles);
            }
        });

        $totalFilter = DB::table(DB::raw("(" . $roleSub->toSql() . ") as tp"))
                ->mergeBindings($roleSub)
                ->count();

        $users = DB::table('users')
                ->select('users.id', 'username', 'fullname', DB::raw("GROUP_CONCAT(roles.name SEPARATOR ',') AS roles"))
                ->leftJoin('role_user', 'user_id', '=', 'users.id')
                ->leftJoin('roles', 'role_id', '=', 'roles.id')
                ->where(function($query) use ($keyword) {
                    $query->where('username', 'LIKE', "%$keyword%")
                    ->orWhere('fullname', 'LIKE', "%$keyword%");
                })
                ->where(function($query) use ($roles) {
                    if (is_array($roles)) {
                        $query->whereIn('role_id', $roles);
                    }
                })
                ->groupBy('username', 'fullname')
                ->orderBy($orderBy, $orderType)
                ->skip($start)
                ->take($length)
                ->get();


        $arr = array(
            'recordsTotal' => $total,
            'data' => $users,
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
                    'username' => 'required|min:4|max:32|regex:/^[a-zA-Z0-9_]{4,32}$/|unique:users,username',
                    'fullname' => 'required|min:4|max:255',
                    'password' => 'required|min:8|max:64|regex:/^(?=.*[a-zA-Z])(?=.*\d).{8,64}$/|confirmed',
                    'email' => 'required|email|unique:users,email',
                    'phone' => 'required|digits_between:7,16|unique:users,phone',
                    'birthday' => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withInput(
                                    $request->except('password')
                            )->withErrors($validator);
        } else {
            $user = new User();
            $user->username = Input::get('username');
            $user->fullname = Input::get('fullname');
            $user->password = bcrypt(Input::get('password'));
            $user->email = Input::get('email');
            $user->birthday = Input::get('birthday');
            $user->phone = Input::get('phone');

            $user->save();

            $roleIdArr = Input::get('roles');
            if (!is_array($roleIdArr)) {
                $roleIdArr = [];
            }
            $roles = Role::whereIn('id', $roleIdArr)
                    ->get();
            $user->attachRoles($roles);

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
        $user = User::findOrFail($id);
        $roles = DB::table('roles')
                ->pluck('display_name', 'id')
                ->toArray();
        $currentRoles = $user->roles->pluck('id')->toArray();
        return view('admin.rbac.users', array(
            'action' => 'edit',
            'user' => $user,
            'roles' => $roles,
            'currentRoles' => $currentRoles
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
        
        $passwordRules = 'required|min:8|max:64|regex:/^(?=.*[a-zA-Z])(?=.*\d).{8,64}$/|confirmed';
        if(Input::get('password') == '') {
            $passwordRules = 'confirmed';
        }
        
        $validator = Validator::make($request->all(), [
                    'username' => "required|min:4|max:32|regex:/^[a-zA-Z0-9_]{4,32}$/|unique:users,username,$id",
                    'fullname' => 'required|min:4|max:255',
                    'password' => $passwordRules,
                    'email' => "required|email|unique:users,email,$id",
                    'phone' => "required|digits_between:7,16|unique:users,phone,$id",
                    'birthday' => 'required|date_format:Y-m-d',
        ]);        
        
        if ($validator->fails()) {
            return redirect()->back()
                            ->withInput(
                                    $request->except('password')
                            )->withErrors($validator);
        } else {
            $user->username = Input::get('username');
            $user->fullname = Input::get('fullname');
            if (Input::get('password') != '') {
                $user->password = bcrypt(Input::get('password'));
            }
            $user->email = Input::get('email');
            $user->birthday = Input::get('birthday');
            $user->phone = Input::get('phone');

            $user->save();

            $roleIdArr = Input::get('roles');
            if (!is_array($roleIdArr)) {
                $roleIdArr = [];
            }
            $roles = Role::whereIn('id', $roleIdArr)
                    ->get();
            $user->detachRoles($user->roles);
            $user->attachRoles($roles);

            return redirect()->back()->with('message', trans('general.update_successfully'));
        }
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

//            DB::table("role_user")->where("role_user.user_id", $id)
//                    ->delete();
        }

        return response()->json($arr);
    }

}
