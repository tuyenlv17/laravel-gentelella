@extends('layouts.common')

@section('title', trans('general.permission'))

@section('main-content')
<div class="row">
    <div class="col-md-4">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{trans("general.".$action)}}</h2>
                <ul class="nav navbar-right panel_toolbox">
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
                {{ Form::open(array('url' => 'admin/permissions', 'id' => 'group-form')) }}
                @else
                {{ Form::model($permission, array('route' => ['permissions.update', $permission->id], 'method' => 'PATCH', 'id' => 'group-form')) }}
                @endif

                <div class="row">
                    <div class="col-sm-12">
                        {{ Form::ctText('name', trans('general.name'), null, [], true) }}
                    </div>
                    <div class="col-sm-12">
                        {{ Form::ctText('display_name', trans('general.display_name'), null, [], true) }}
                    </div>
                    <div class="col-sm-12">
                        {{ Form::ctText('description', trans('general.description'), null, [], false) }}
                    </div>  
                    <div class="col-sm-12">
                        {{ Form::ctSelect('group', trans('general.group'), $groups, (strcmp($action,'add') != 0) ? $current_group : NULL, [], true) }}
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
                <table id="permission-table" class="table table-striped table-bordered dt-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{trans('general.name')}}</th>
                            <th>{{trans('general.display_name')}}</th>
                            <th>{{trans('general.description')}}</th>
                            <th>{{trans('general.group')}}</th>
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
@stop

@section('assets_js')
<!-- Datatables -->
<script src="{{asset('/resources/vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/resources/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('/resources/js/admin/permission.js')}}"></script>
@stop