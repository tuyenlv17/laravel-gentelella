@extends('layouts.common')

@section('title', trans('general.users'))

@section('main-content')
<div class="row">
    <div class="col-md-4">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{trans("general.".$action)}}</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a href="{{url('/admin/rbac/users')}}"><i class="fa fa-plus" title="{{trans('general.add')}}"></i></a>
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>                    
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @include('components.common.alert')
                @if(strcmp($action,'add') === 0)
                {{ Form::open(array('url' => 'admin/rbac/users', 'id' => 'user-form')) }}
                @else
                {{ Form::model($user, array('route' => ['users.update', $user->id], 'method' => 'PATCH', 'id' => 'user-form')) }}
                @endif
                <div class="row">
                    <div class="col-sm-12">
                        {{ Form::ctText('username', trans('general.username'), null, [], true) }}
                    </div>
                    <div class="col-sm-12">
                        {{ Form::ctText('fullname', trans('general.fullname'), null, [], true) }}
                    </div>
                    <div class="col-sm-12">
                        {{ Form::ctText('email', trans('general.email'), null, [], false) }}
                    </div>
                    <div class="col-sm-12">
                        {{ Form::ctText('phone', trans('general.phone'), null, [], false) }}
                    </div>
                    <div class="col-sm-12">
                        {{ Form::ctText('birthday', trans('general.birthday'), null, ['class' => 'date-picker'], false) }}
                    </div>
                    <div class="col-sm-12">
                        {{ Form::ctPassword('password', trans('general.password'), null, [], strcmp($action,'add') === 0) }}
                    </div>
                    <div class="col-sm-12">
                        {{ Form::ctPassword('password_confirmation', trans('general.password_confirmation'), null, [], strcmp($action,'add') === 0) }}
                    </div>
                    <div class="col-sm-12">
                        {{ Form::ctSelect('roles[]', trans('general.roles'), $roles, strcmp($action,'add') === 0 ? NULL:$currentRoles, ['multiple' => 'multiple', 'class' =>'select2-mutiple'], true) }}
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

    <div class="col-md-8">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{trans("general.list")}}</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>                    
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table id="users-table" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{trans('general.username')}}</th>
                            <th>{{trans('general.fullname')}}</th>
                            <th>{{trans('general.roles')}}</th>
                            <th>{{trans('general.action')}}</th>                        
                        </tr>                        
                    </thead>
                </table>
            </div>
        </div>               
    </div>
</div>
@stop

@section('assets_css')
<!-- Datatables -->
<link href="{{asset('/resources/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
<link href="{{asset('/resources/vendors/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
<link href="{{asset('/resources/vendors/jquery-confirm2/dist/jquery-confirm.min.css')}}" rel="stylesheet">
<link href="{{asset('/resources/vendors/toastr/toastr.min.css')}}" rel="stylesheet">
@stop

@section('assets_js')
<!-- Datatables -->
<script src="{{asset('/resources/vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/resources/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<!-- bootstrap-daterangepicker -->
<script src="{{asset('/resources/vendors/moment/min/moment.min.js')}}"></script>
<script src="{{asset('/resources/vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('/resources/vendors/jquery-confirm2/dist/jquery-confirm.min.js')}}"></script>
<script src="{{asset('/resources/vendors/toastr/toastr.min.js')}}"></script>
<script src="{{asset('/resources/js/admin/rbac/users.js')}}"></script>
@stop