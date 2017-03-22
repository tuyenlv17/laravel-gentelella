<?php

namespace App\Http\Controllers\Management;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Genre;
use DB;

class GenreController extends Controller {

    public function __construct() {
        $this->middleware('auth');
//        $this->middleware('genre:management-genre-crud', ['except' => []]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('management.genres', array(
            'action' => 'add',
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

        $total = Genre::count();

        $totalFilter = DB::table('genres')
                ->where(function ($query) use ($keyword) {
                    $query->where('genres.name', 'LIKE', "%$keyword%");
                })
                ->count();

        $genres = DB::table('genres')
                ->select('genres.*', DB::raw("COUNT(movies.id)"))
                ->leftJoin('movies_genres', 'genre_id', '=', 'genres.id')
                ->leftJoin('movies', 'movies.id', '=', 'movie_id')
                ->where(function ($query) use ($keyword) {
                    $query->where('genres.name', 'LIKE', "%$keyword%");
                })
                ->orderBy($orderBy, $orderType)
                ->skip($start)
                ->take($length)
                ->get();


        $arr = array(
            'recordsTotal' => $total,
            'data' => $genres,
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
                    'name' => 'required|min:4|max:255|unique:genres,name',
        ]);

        if ($validator->fails()) {
            return redirect('management/genres')
                            ->withInput()
                            ->withErrors($validator);
        } else {
            $genre = new Genre();
            $genre->name = Input::get('name');
            $genre->save();
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
        $genre = Genre::findOrFail($id);
        return view('management.genres', array(
            'action' => 'edit',
            'genre' => $genre,
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
        $genre = Genre::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|min:4|max:255|unique:genres,name,' . $genre->id,
        ]);
        $genre->name = Input::get('name');
        $genre->save();

        return redirect()->back()->with('message', trans('general.update_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $genre = Genre::findOrFail($id);

        $arr = array(
            'code' => 1,
            'message' => 'error'
        );

        if (DB::table("genres")->where('id', $id)->delete()) {
            $arr = array(
                'code' => 0,
                'message' => 'success'
            );
        }

        return response()->json($arr);
    }

}