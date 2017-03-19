@extends('layouts.main')

@section('page-content')
<!-- sidebar -->
@include('layouts.sidebar')
<!-- /sidebar -->

<!-- header -->
@include('layouts.header')
<!-- /header -->

<!-- page content -->
@section('main-section')
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3> @yield('title', 'blabla') </h3>
            </div>

            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    @include('components.common.search-box')
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
        
        @section('main-content')
        
        @show
    </div>
</div>
@show
<!-- /page content -->

<!-- footer content -->
<footer>
    <div class="pull-right">
        Blabla - Created by <a href="#">Blalba</a>
    </div>
    <div class="clearfix"></div>
</footer>
<!-- /footer content -->
@stop