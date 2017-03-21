@extends('layouts.common')

@section('title', trans('general.roles'))

@section('main-content')
<div class="row">
    <div class="col-md-4">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{trans("general.".$action)}}</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a href="{{url('/admin/rbac/roles')}}"><i class="fa fa-plus" title="{{trans('general.add')}}"></i></a>
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
                {{ Form::open(array('url' => 'admin/roles', 'id' => 'role-form')) }}
                @else
                {{ Form::model($role, array('route' => ['roles.update', $role->id], 'method' => 'PATCH', 'id' => 'role-form')) }}
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
                        {{ Form::ctText('default_url', trans('general.default_url'), null, [], true) }}
                    </div>
                    <div class="col-md-12">
                        <div class="accordion" id="accordion accordion-role" role="tablist" aria-multiselectable="true">
                            @foreach($permission_group as $group_name=>$permissions)
                            <div class="panel">
                                <a class="panel-heading collapsed" role="tab" id="group-header-{{$loop->index}}" data-parent="#accordion-role" data-toggle="collapse" href="#group-{{$loop->index}}" aria-expanded="false" aria-controls="#group-{{$loop->index}}">                                    
                                    <h4 class="panel-title">
                                        {{ Form::checkbox("group-header-".$loop->index, null, false, []) }}
                                        {{$group_name}}
                                    </h4>
                                </a>
                                <div id="group-{{$loop->index}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="#group-header-{{$loop->index}}">                                    
                                    <div class="panel-body">
                                        @foreach($permissions as $permission)
                                        @if(!isset($permission->id)) 
                                            @continue
                                        @endif
                                        <div class="row" style="margin-bottom: 5px;">
                                            @if(strcmp($action,'add') === 0)                                        
                                            {{ Form::checkbox('permission[]', $permission->id, false, []) }}
                                            @else
                                            {{ Form::checkbox('permission[]', $permission->id, in_array($permission->id, $current_permisisons) ? true : false, []) }}
                                            @endif                                    
                                            {{ $permission->display_name }}
                                        </div>                                            
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>                        
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
                <table id="roles-table" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{trans('general.name')}}</th>
                            <th>{{trans('general.display_name')}}</th>
                            <th>{{trans('general.description')}}</th>
                            <th>{{trans('general.default_url')}}</th>
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
<link href="{{asset('/resources/vendors/iCheck/skins/square/_all.css')}}" rel="stylesheet">
@stop

@section('assets_js')
<!-- Datatables -->
<script src="{{asset('/resources/vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/resources/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('/resources/vendors/iCheck/icheck.min.js')}}"></script>
<script src="{{asset('/resources/js/admin/rbac/roles.js')}}"></script>
@stop