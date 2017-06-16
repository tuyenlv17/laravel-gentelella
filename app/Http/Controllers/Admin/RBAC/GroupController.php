<?php

namespace App\Http\Controllers\Admin\RBAC;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Group;

class GroupController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:rbac-group-crud', ['except' => []]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.rbac.groups', array(
            'action' => 'add',
        ));
    }

    /**
     * List data of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing()
    {
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

        $total = Group::count();

        $totalFilter = Group::
        where(function ($query) use ($keyword) {
            $query->where('name', 'LIKE', "%$keyword%")
                ->orWhere('display_name', 'LIKE', "%$keyword%")
                ->orWhere('description', 'LIKE', "%$keyword%");
        })
            ->count();

        $groups = Group::
        where(function ($query) use ($keyword) {
            $query->where('name', 'LIKE', "%$keyword%")
                ->orWhere('display_name', 'LIKE', "%$keyword%")
                ->orWhere('description', 'LIKE', "%$keyword%");
        })
            ->orderBy($orderBy, $orderType)
            ->skip($start)
            ->take($length)
            ->get();


        $arr = array(
            'recordsTotal' => $total,
            'data' => $groups,
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:48|unique:groups,name',
            'display_name' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        } else {
            $group = new Group();
            $group->name = Input::get('name');
            $group->display_name = Input::get('display_name');
            $group->description = Input::get('description');

            $group->save();

            return redirect()->back()->with('message', trans('general.add_successfully'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $group = Group::findOrFail($id);
        return view('admin.rbac.groups', array(
            'action' => 'edit',
            'group' => $group,
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|max:48|unique:groups,name,' . $group->id,
            'display_name' => 'required',
        ]);

        $group->name = Input::get('name');
        $group->display_name = Input::get('display_name');
        $group->description = Input::get('description');
        $group->save();

        return redirect()->back()->with('message', trans('general.update_successfully'));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
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
