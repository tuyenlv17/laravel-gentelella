@extends('layouts.error')

@section('title', trans('general.error_404_pt'))

@section('error-page-detail')
<h1 class="error-number"> 404 </h1>
<h2> {{trans('general.error_404_title')}} </h2>
<p>
    {{trans('general.error_404_detail')}} <a href="#">{{trans('general.report_this')}}</a>
</p>
@stop