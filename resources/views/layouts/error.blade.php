@extends('layouts.main')

@section('main-contain')

<div class="col-md-12">
    <div class="col-middle">
        <div class="text-center text-center">
            <h1 class="error-number"> @yield('error_code', 500) </h1>
            <h2> @yield('error_title', trans('general.error_500_title')) </h2>
            <p>
                @yield('error_detail', trans('general.error_500_detail')) <a href="#">{{trans('general.report_this')}}</a>
            </p>
            <p>
                <a href="{{url('/')}}">{{trans('general.back_to_home')}}</a>
            </p>
            <div class="mid_center">
                <h3>{{trans('general.search')}}</h3>
                <form>
                    <div class="col-xs-12 form-group pull-right top_search">
                        @include('components.common.search-box')
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@show