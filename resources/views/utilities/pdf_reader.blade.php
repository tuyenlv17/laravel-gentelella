@extends('layouts.common')

@section('title', trans('general.users'))

@section('main-content')
<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{trans("general.pdf_reader")}}</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>                    
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <iframe style="width: 100%" height="720px" src="{{ url('/utilities/pdf_reader_full') }}"></iframe>
            </div>
        </div>        
    </div>
</div>
@stop

@section('assets_css')
<!-- Datatables -->
<link href="{{asset('/resources/vendors/toastr/toastr.min.css')}}" rel="stylesheet">
@stop

@section('assets_js')
<script src="{{asset('/resources/vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('/resources/vendors/jquery-confirm2/dist/jquery-confirm.min.js')}}"></script>
<script src="{{asset('/resources/vendors/toastr/toastr.min.js')}}"></script>
@stop