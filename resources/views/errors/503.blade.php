@extends('layouts.app')

@section('title', trans('general.error_503_title'))

@section('error-page-detail')
<h1 class="error-number"> 503 </h1>
<h2> {{trans('general.error_503_title')}} </h2>
<p>
    {{trans('general.error_503_detail')}} <a href="#">{{trans('general.report_this')}}</a>
</p>
@endsection