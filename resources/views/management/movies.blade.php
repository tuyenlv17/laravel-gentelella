@extends('layouts.common')

@section('title', trans('general.movies'))

@section('main-content')
<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{trans("general.".$action)}}</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a href="{{url('/management/movies')}}"><i class="fa fa-plus" title="{{trans('general.add')}}"></i></a>
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
                {{ Form::open(array('url' => 'management/movies', 'id' => 'movie-form')) }}
                @else
                {{ Form::model($movie, array('route' => ['movies.update', $movie->id], 'method' => 'PATCH', 'id' => 'movie-form')) }}
                @endif
                <div class="row">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="col-sm-12">
                                {{ Form::ctText('title', trans('general.title'), null, [], true) }}
                            </div>
                            <div class="col-sm-12">
                                {{ Form::ctText('year', trans('general.year'), null, [], true) }}
                            </div>
                            <div class="col-sm-12">
                                {{ Form::ctText('price', trans('general.price'), null, [], false) }}
                            </div>
                            <div class="col-sm-12">
                                {{ Form::ctText('dis_price', trans('general.dis_price'), null, [], false) }}
                            </div>       
                            <div class="col-sm-12">
                                {{ Form::ctTextArea('plot', trans('general.plot'), null, [], false) }}
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="col-sm-12">
                                {{ Form::ctSelect('genres[]', trans('general.genres'), $genres, strcmp($action,'add') === 0 ? NULL:$currentGenres, ['multiple' => 'multiple', 'class' =>'select2-mutiple'], true) }}
                            </div>                                        
                            <div class="col-md-12">
                                <label class="control-label">{{trans('general.attribute_value')}}</label>
                                <div class="accordion" id="accordion accordion-role" role="tablist" aria-multiselectable="true">
                                    @foreach($attributes as $attributeName=>$attributeValues)
                                    <div class="panel">
                                        <a class="panel-heading collapsed" role="tab" id="group-header-{{$loop->index}}" data-parent="#accordion-role" data-toggle="collapse" href="#group-{{$loop->index}}" aria-expanded="false" aria-controls="#group-{{$loop->index}}">                                    
                                            <h4 class="panel-title">
                                                {{ Form::checkbox("group-header-".$loop->index, null, false, []) }}
                                                {{$attributeName}}
                                            </h4>
                                        </a>
                                        <div id="group-{{$loop->index}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="#group-header-{{$loop->index}}">                                    
                                            <div class="panel-body">
                                                <div class="row" style="margin-bottom: 5px;">
                                                    @foreach($attributeValues as $attributeValue)                                                                                        
                                                    <div class="col-md-4">
                                                        @if(strcmp($action,'add') === 0)                                        
                                                        {{ Form::checkbox('attribute_val[]', $attributeValue->id, false, []) }}
                                                        @else
                                                        {{ Form::checkbox('attribute_val[]', $attributeValue->id, in_array($attributeValue->id, $currentAttributeVals) ? true : false, []) }}
                                                        @endif                                    
                                                        {{ $attributeValue->name }}
                                                    </div>                                                         
                                                    @endforeach
                                                </div>                                       
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
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

</div>

<div class='row'>
    <div class="col-md-12">
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
                <div class="row">
                    <div class="col-sm-4">
                        {{ Form::ctSelect('genres_filter[]', trans('general.genres'), $genres, NULL, ['multiple' => 'multiple', 'class' =>'select2-mutiple genres-filter'], true) }}
                    </div>
                </div>                
                <table id="movies-table" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{trans('general.title')}}</th>
                            <th>{{trans('general.year')}}</th>
                            <th>{{trans('general.price')}}</th>
                            <th>{{trans('general.dis_price')}}</th>                        
                            <th>{{trans('general.plot')}}</th>                        
                            <th>{{trans('general.genres')}}</th>                        
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
<link href="{{asset('/resources/vendors/iCheck/skins/square/_all.css')}}" rel="stylesheet">
@stop

@section('assets_js')
<!-- Datatables -->
<script src="{{asset('/resources/vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/resources/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<!-- bootstrap-daterangepicker -->
<script src="{{asset('/resources/vendors/moment/min/moment.min.js')}}"></script>
<script src="{{asset('/resources/vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('/resources/vendors/iCheck/icheck.min.js')}}"></script>
<script src="{{asset('/resources/js/management/movies.js')}}"></script>
@stop