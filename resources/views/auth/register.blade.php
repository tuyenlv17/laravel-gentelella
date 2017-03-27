@extends('layouts.main')

@section('title', trans('general.login'))

@section('page-content')
<div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>
    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                {{ Form::open(array('url' => 'register', 'id' => 'register-form')) }}
                    <h1>{{trans('general.sign_up')}}</h1>
                    @include('components.common.alert')
                    <div>
                        <input type="text" name="username" value="{{ old('username') }}" class="form-control" placeholder="{{trans('general.username')}}" required="" />
                    </div>
                    <div>
                        <input type="text" name="fullname" value="{{ old('fullname') }}" class="form-control" placeholder="{{trans('general.fullname')}}" required="" />
                    </div>
                    <div>
                        <input type="text" name="email" value="{{ old('email') }}" class="form-control" placeholder="{{trans('general.email')}}" required="" />
                    </div>
                    <div>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="{{trans('general.phone')}}" required="" />
                    </div>
                    <div>
                        <input type="text" name="birthday" value="{{ old('birthday') }}" class="form-control" placeholder="{{trans('general.birthday')}}" required="" />
                    </div>
                    <div>
                        <input type="password" name="password" class="form-control" placeholder="{{trans('general.password')}}" required="" />
                    </div>
                    <div>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="{{trans('general.password_confirmation')}}" required="" />
                    </div>
                    <div class="captcha">
                        <i id="captcha-reload" class="fa fa-refresh"></i>
                        <img id="captcha-img" alt="captcha" src="{{captcha_src()}}" onclick="this.src='/captcha/default?'+Math.random()">
                        <div class="captcha-input">
                            <input type="text" name="captcha" class="form-control" placeholder="{{trans('general.captcha')}}" required="" />
                        </div>                        
                    </div>
                    <div>
                        <button type="submit" class="btn btn-default submit">{{trans('general.sign_up')}}</button>
                    </div>
                    <div class="clearfix"></div>
                    <div class="separator">
                        <p class="change_link">{{trans('general.already_member')}}
                            <a href="{{url('/login')}}" class="to_login"> {{trans('general.login')}} </a>
                        </p>
                        <div class="clearfix"></div>
                        <br />
                        <div>
                            <h1><i class="fa fa-resistance"></i> MoviesCreed </h1>
                            <p>©2016 All Rights Reserved.</p>
                        </div>
                    </div>
                {{ Form::close() }}
            </section>
        </div>
    </div>
</div>
@endsection

@section('assets_js')
<script src="{{asset('/resources/vendors/moment/min/moment.min.js')}}"></script>
<script src="{{asset('/resources/vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('/resources/js/site/login.js')}}"></script>
@stop