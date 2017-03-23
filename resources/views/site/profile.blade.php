@extends('layouts.common')

@section('title', trans('general.profile'))

@section('main-content')
<div class="row">
    <div class="col-md-6">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{trans("general.profile")}}</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @include('components.common.alert')
                
                {{ Form::model($user, array('id' => 'profile-form')) }}                
                <div class="row">
                    <div class="col-md-12">
                        {{ Form::ctText('username', trans('general.username'), null, ['disabled' => 'true'], true) }}
                    </div>
                    <div class="col-md-12">
                        {{ Form::ctText('fullname', trans('general.fullname'), null, [], true) }}
                    </div>
                    <div class="col-md-12">
                        {{ Form::ctText('email', trans('general.email'), null, [], false) }}
                    </div>
                    <div class="col-md-12">
                        {{ Form::ctText('phone', trans('general.phone'), null, [], false) }}
                    </div>
                    <div class="col-md-12">
                        {{ Form::ctText('birthday', trans('general.birthday'), null, ['class' => 'date-picker'], false) }}
                    </div>
                    <div class="col-md-12">
                        {{ Form::ctPassword('password', trans('general.password'), null, [], false) }}
                    </div>
                    <div class="col-md-12">
                        {{ Form::ctPassword('password_confirmation', trans('general.password_confirmation'), null, [], false) }}
                    </div>
                    <div class="col-md-12">
                        {{ Form::submit(trans('general.submit'), array('class' => 'btn btn-primary')) }}
                        {{ Form::reset(trans('general.cancel'), array('class' => 'btn btn-default')) }}
                    </div>                    
                </div>

                {{ Form::close() }}
            </div>
        </div>        
    </div>
</div>
@stop

@section('assets_css')
<!-- Datatables -->
<link href="{{asset('/resources/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
<link href="{{asset('/resources/vendors/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
@stop

@section('assets_js')
<!-- Datatables -->
<script src="{{asset('/resources/vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/resources/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<!-- bootstrap-daterangepicker -->
<script src="{{asset('/resources/vendors/moment/min/moment.min.js')}}"></script>
<script src="{{asset('/resources/vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('/resources/js/site/profile.js')}}"></script>
@stop