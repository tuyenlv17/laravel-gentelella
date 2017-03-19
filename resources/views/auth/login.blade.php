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
                    <div>
                        <input type="text" name="username" value="{{ old('username') }}" class="form-control" placeholder="{{trans('general.username')}}" required="" />
                    </div>
                    <div>
                        <input type="password" name="password" class="form-control"  placeholder="{{trans('general.password')}}" required="" />                        
                    </div>
                    <div>
                        <button type="submit" class="btn btn-default submit">{{trans('general.login')}}</button>
                        <a class="reset_pass" href="#">{{trans('general.forgot_pass')}}</a>
                    </div>
                    <div class="clearfix"></div>
                    <div class="separator">
                        <p class="change_link">{{trans('general.new_to_site')}}
                            <a href="#signup" class="to_register"> {{trans('general.sign_up')}} </a>
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
        <div id="register" class="animate form registration_form">
            <section class="login_content">
                {{ Form::open(array('url' => 'register', 'id' => 'login-form')) }}
                    <h1>{{trans('general.singup')}}</h1>
                    <div>
                        <input type="text" name="username" value="{{ old('username') }}" class="form-control" placeholder="{{trans('general.username')}}" required="" />
                    </div>
                    <div>
                        <input type="text" name="email" value="{{ old('email') }}" class="form-control" placeholder="{{trans('general.email')}}" required="" />
                    </div>
                    <div>
                        <input type="password" name="password" class="form-control" placeholder="{{trans('general.password')}}" required="" />
                    </div>
                    <div>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="{{trans('general.password_confirmation')}}" required="" />
                    </div>
                    <div>
                        <button type="submit" class="btn btn-default submit">{{trans('general.singup')}}</button>
                    </div>
                    <div class="clearfix"></div>
                    <div class="separator">
                        <p class="change_link">{{trans('general.already_member')}}
                            <a href="#signin" class="to_register"> {{trans('general.login')}} </a>
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
