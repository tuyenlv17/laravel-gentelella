<?php

namespace App\Http\Controllers\Management;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Movie;
use App\Genre;
use Validator;

class MovieController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth');
//        $this->middleware('permission:rbac-movie-crud', ['except' => []]);
    }

    public function index() {
        $genres = DB::table('genres')
                ->pluck('name', 'id')
                ->toArray();
        return view('management.movies', array(
            'action' => 'add',
            'genres' => $genres
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

        $genres = Input::get('genres');

        $total = Movie::count();

        $genreSub = DB::table('movies')
                ->select(DB::raw("distinct(movies.id)"))
                ->leftJoin('movies_genres', 'movie_id', '=', 'movies.id')
                ->where(function($query) use ($keyword) {
                    $query->where('title', 'LIKE', "%$keyword%")
                            ->orWhere('year', 'LIKE', "%$keyword%")
                            ->orWhere('plot', 'LIKE', "%$keyword%")
                            ->orWhere('price', 'LIKE', "%$keyword%")
                            ->orWhere('dis_price', 'LIKE', "%$keyword%");;
                })
                ->where(function($query) use ($genres) {
                    if (is_array($genres)) {
                        $query->whereIn('genre_id', $genres);
                    }
                });

        $totalFilter = DB::table(DB::raw("(" . $genreSub->toSql() . ") as tp"))
                ->mergeBindings($genreSub)
                ->count();

        $movies = DB::table('movies')
                ->select('movies.*', DB::raw("GROUP_CONCAT(genres.name SEPARATOR ',') AS genres"))
                ->leftJoin('movies_genres', 'movie_id', '=', 'movies.id')
                ->leftJoin('genres', 'genre_id', '=', 'genres.id')
                ->where(function($query) use ($keyword) {
                    $query->where('title', 'LIKE', "%$keyword%")
                            ->orWhere('year', 'LIKE', "%$keyword%")
                            ->orWhere('plot', 'LIKE', "%$keyword%")
                            ->orWhere('price', 'LIKE', "%$keyword%")
                            ->orWhere('dis_price', 'LIKE', "%$keyword%");;
                })
                ->where(function($query) use ($genres) {
                    if (is_array($genres)) {
                        $query->whereIn('genre_id', $genres);
                    }
                })
                ->groupBy('movies.id')
                ->orderBy($orderBy, $orderType)
                ->skip($start)
                ->take($length)
                ->get();


        $arr = array(
            'recordsTotal' => $total,
            'data' => $movies,
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
        $floatRegex = '/[+-]?([\d]*[.])?[\d]+/';
        
        $validator = Validator::make($request->all(), [
                    'title' => 'required|min:1|max:255|regex:/^[a-zA-Z\d \"\"\(\)]+$/',
                    'year' => 'required|date_format:Y',
                    'plot' => 'required|min:4|max:1024',
                    'price' => 'required|regex:' . $floatRegex,
                    'dis_price' => 'required|regex:' . $floatRegex,
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {
            
            $price = floatval(Input::get('price'));
            $disPrice = floatval(Input::get('dis_price'));
            
            if($disPrice > $price) {
                return redirect()->back()
                        ->withInput()
                        ->withErrors(trans('general.invalid_price'));
            }
            
            $movie = new Movie();
            $movie->title = Input::get('title');
            $movie->year = Input::get('year');
            $movie->plot = Input::get('plot');
            $movie->price = $price;
            $movie->dis_price = $disPrice;
            
            $movie->save();

            echo $movie->id;
            return;
            
            $genreIdArr = Input::get('genres');
            if (!is_array($genreIdArr)) {
                $genreIdArr = [];
            }
            $genres = Genre::whereIn('id', $genreIdArr)
                    ->get();
            
            foreach($genres as $genre) {
                DB::statement("INSERT INTO movies_genres(movie_id,genre_id) VALUES($movie->id, $genre->id)");
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
        $movie = Movie::findOrFail($id);
        $genres = DB::table('genres')
                ->pluck('name', 'id')
                ->toArray();
        
        $currentGenres = DB::table('genres')
                ->join('movies_genres', function($join) use ($movie) {
                    $join->on('genre_id', '=', 'genres.id')
                         ->where('movie_id', '=', $movie->id);                    
                })
                ->pluck('genres.id')
                ->toArray();
                
        return view('management.movies', array(
            'action' => 'edit',
            'movie' => $movie,
            'genres' => $genres,
            'currentGenres' => $currentGenres
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

        $movie = Movie::findOrFail($id);
        
        $floatRegex = '/[+-]?([\d]*[.])?[\d]+/';
        
        $validator = Validator::make($request->all(), [
                    'title' => 'required|min:1|max:255|regex:/^[a-zA-Z\d \"\"\(\)]+$/',
                    'year' => 'required|date_format:Y',
                    'plot' => 'required|min:4|max:1024',
                    'price' => 'required|regex:' . $floatRegex,
                    'dis_price' => 'required|regex:' . $floatRegex,
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {
            
            $price = floatval(Input::get('price'));
            $disPrice = floatval(Input::get('dis_price'));
            
            if($disPrice > $price) {
                return redirect()->back()
                        ->withInput()
                        ->withErrors(trans('general.invalid_price'));
            }
            
            $movie->title = Input::get('title');
            $movie->year = Input::get('year');
            $movie->plot = Input::get('plot');
            $movie->price = $price;
            $movie->dis_price = $disPrice;
            
            $movie->save();
            
            DB::table('movies_genres')
                    ->where('movie_id', '=', $movie->id)
                    ->delete();            
            
            $genreIdArr = Input::get('genres');
            if (!is_array($genreIdArr)) {
                $genreIdArr = [];
            }
            $genres = Genre::whereIn('id', $genreIdArr)
                    ->get();
            
            foreach($genres as $genre) {
                DB::statement("INSERT INTO movies_genres(movie_id,genre_id) VALUES($movie->id, $genre->id)");
            }

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
        $movie = Movie::findOrFail($id);

        $arr = array(
            'code' => 1,
            'message' => 'error'
        );

        if (DB::table("movies")->where('id', $id)->delete()) {
            $arr = array(
                'code' => 0,
                'message' => 'success'
            );

//            DB::table("movies_genres")->where("movies_genres.movie_id", $id)
//                    ->delete();
        }

        return response()->json($arr);
    }

}
