<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title> Blabla | @yield('title', 'blabla')</title>

        <!-- Bootstrap -->
        <link href="{{asset('/resources/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="{{asset('/resources/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
        <!-- bootstrap-daterangepicker -->
        <link href="{{asset('/resources/vendors/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
        <!-- Animate.css -->
        <link href="{{asset('/resources/vendors/animate.css/animate.min.css')}}" rel="stylesheet">
        
        <!-- Custom Theme Style -->
        <link href="{{asset('/resources/css/app.css')}}" rel="stylesheet">

        <script>
            window.Laravel = {!! json_encode([
                    'csrfToken' => csrf_token(),
            ]) !!}
            ;
        </script>
    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                @section('admin-page')
                <!-- sidebar -->
                @include('sidebar')
                <!-- /sidebar -->
                
                <!-- header -->
                
                <!-- /header -->

                <!-- page content -->
                
                <!-- /page content -->

                <!-- footer content -->
                <footer>
                    <div class="pull-right">
                        Blabla - Created by <a href="#">Blalba</a>
                    </div>
                    <div class="clearfix"></div>
                </footer>
                <!-- /footer content -->
                @show
                
                <!-- error -->
                @section('error-page')
                <div class="col-md-12">
                    <div class="col-middle">
                        <div class="text-center text-center">
                            @section('error-page-detail')
                            <h1 class="error-number"> 500 </h1>
                            <h2> {{trans('general.error_500_title')}} </h2>
                            <p>
                                {{trans('general.error_500_detail')}} <a href="#">{{trans('general.report_this')}}</a>
                            </p>
                            @show
                            <div class="mid_center">
                                <h3>{{trans('general.search')}}</h3>
                                <form>
                                    <div class="col-xs-12 form-group pull-right top_search">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="{{trans('general.search_for')}}">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @show
                <!-- /error -->
                
                <!-- login -->
                @section('login-page')
                
                @show
                <!-- /login -->
            </div>
        </div>        
        <!-- jQuery -->
        <script src="{{asset('/resources/vendors/jquery/dist/jquery.min.js')}}"></script>
        <!-- Bootstrap -->
        <script src="{{asset('/resources/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
        <!-- Custom Theme Scripts -->
        <script src="{{asset('/resources/js/app.min.js')}}"></script>
        <script src="{{asset('/resources/js/custom.js')}}"></script>
    </body>
</html>
