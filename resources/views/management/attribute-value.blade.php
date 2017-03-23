@extends('layouts.common')

@section('title', trans('general.attribute_val'))

@section('main-content')
<div class="row">
    <div class="col-md-4">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{trans("general.".$action)}}</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a href="{{url('/management/attribute_val')}}"><i class="fa fa-plus" title="{{trans('general.add')}}"></i></a>
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
                {{ Form::open(array('url' => 'management/attribute_val', 'id' => 'attribute_val-form')) }}
                @else
                {{ Form::model($attributeValue, array('route' => ['attribute_val.update', $attributeValue->id], 'method' => 'PATCH', 'id' => 'attribute_val-form')) }}
                @endif

                <div class="row">
                    <div class="col-sm-12">
                        {{ Form::ctText('name', trans('general.name'), null, [], true) }}
                    </div>  
                    <div class="col-sm-12">
                        {{ Form::ctSelect('attribute', trans('general.attribute'), $attributes, (strcmp($action,'add') != 0) ? $attributeValue->attribute_id : NULL, ['class' => 'select2-single'], true) }}
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
                <div class="col-sm-4">
                    {{ Form::ctSelect('attribute-filter', trans('general.attribute'), $attributes, (strcmp($action,'add') != 0) ? $attributeValue->attribute_id : NULL, ['class' => 'select2-single'], true) }}
                </div>
                <table id="attribute_val-table" class="table table-striped table-bordered dt-responsive nowrap">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{trans('general.name')}}</th>
                            <th>{{trans('general.attribute')}}</th>
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
<script src="{{asset('/resources/js/management/attribute.value.js')}}"></script>
@stop