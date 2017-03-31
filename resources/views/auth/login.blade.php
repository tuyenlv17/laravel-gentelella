@extends('layouts.main')

@section('title', trans('general.login'))

@section('page-content')
<div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>
    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                {{ Form::open(array('url' => 'login', 'id' => 'login-form')) }}
                    <h1>{{trans('general.login')}}</h1>
                    @include('components.common.alert')
                    <div>
                        <input type="text" name="username" value="{{ old('username') }}" class="form-control" placeholder="{{trans('general.username')}}" required="" />
                    </div>
                    <div>
                        <input type="password" name="password" class="form-control"  placeholder="{{trans('general.password')}}" required="" />                        
                    </div>
                    @if(Cache::has('captcha_login'))
                    <div class="captcha">
                        <i id="captcha-reload" class="fa fa-refresh"></i>
                        <img id="captcha-img" alt="captcha" src="{{captcha_src()}}" onclick="this.src='/captcha/default?'+Math.random()">
                        <div class="captcha-input">
                            <input type="text" name="captcha" class="form-control" placeholder="{{trans('general.captcha')}}"/>
                        </div>                        
                    </div>
                    @endif
                    <div class="text-left" style="margin-bottom: 10px;">
                        {{ Form::checkbox('remember', null, false, []) }} {{trans('general.remember_me')}}
                    </div>
                    <div>
                        <button type="submit" class="btn btn-default submit">{{trans('general.login')}}</button>
                        <a class="reset_pass" href="#">{{trans('general.forgot_pass')}}</a>
                    </div>
                    <div class="clearfix"></div>
                    <div class="separator">
                        <p class="change_link">{{trans('general.new_to_site')}}
                            <a href="{{url('/register')}}" class="to_register"> {{trans('general.sign_up')}} </a>
                        </p>
                        <div class="clearfix"></div>
                        <br />
                        <div>
                            <h1><i class="fa fa-resistance"></i> Blabla</h1>
                            <p>©2016 All Rights Reserved.</p>
                        </div>
                    </div>
                {{ Form::close() }}
            </section>
        </div>        
    </div>
</div>
@endsection

@section('assets_css')
<!-- Datatables -->
<link href="{{asset('/resources/vendors/iCheck/skins/square/_all.css')}}" rel="stylesheet">
@stop

@section('assets_js')
<script src="{{asset('/resources/vendors/moment/min/moment.min.js')}}"></script>
<script src="{{asset('/resources/vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('/resources/vendors/iCheck/icheck.min.js')}}"></script>
<script src="{{asset('/resources/js/site/login.js')}}"></script>
@stop