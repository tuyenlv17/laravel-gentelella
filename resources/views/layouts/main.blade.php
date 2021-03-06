<!DOCTYPE html>
<html lang="{{ Session::get('locale', Config::get('app.fallback_locale')) }}">
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
        <!-- select2 -->
        <link href="{{asset('/resources/vendors/select2/dist/css/select2.min.css')}}" rel="stylesheet">                
        
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
        @yield('assets_css')        
        
        <!-- Custom Theme Style -->
        <link href="{{asset('/resources/css/app.css')}}" rel="stylesheet">
    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                @section('page-content')
                @show
            </div>
        </div>  
        <!-- js csrf -->
        <script>
            window.Laravel = {!! json_encode([
                    'csrfToken' => csrf_token(),
            ]) !!}
            ;
        </script>
        <!-- js-languege -->
        <script src="{{asset('/resources/js/lang/' . Session::get('locale', Config::get('app.fallback_locale')) . '.js')}}"></script>        
        <!-- jQuery -->
        <script src="{{asset('/resources/vendors/jquery/dist/jquery.min.js')}}"></script>
        <!-- Bootstrap -->
        <script src="{{asset('/resources/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
        <!-- select2 -->
        <script src="{{asset('/resources/vendors/select2/dist/js/select2.full.min.js')}}"></script>
        <!-- moment -->
        <script src="{{asset('/resources/vendors/moment/min/moment-with-locales.min.js')}}"></script>
        <script>          
            moment.locale($('html').attr('lang'));
        </script>
        <!-- App Scripts -->        
        <script src="{{asset('/resources/js/app.js')}}"></script>
        @yield('assets_js')       
        <!-- Custom Scripts -->
        <script src="{{asset('/resources/js/custom.js')}}"></script>
        <input type="hidden" id="site-meta"             
               data-base-url="{{ url('/') }}"/>
    </body>
</html>
