@extends('layouts.error')

@section('title', trans('general.error_500_title'))

@section('error-page-detail')
<h1 class="error-number"> 500 </h1>
<h2> {{trans('general.error_500_title')}} </h2>
<p>
    {{trans('general.error_500_detail')}} <a href="#">{{trans('general.report_this')}}</a>
</p>
@endsection