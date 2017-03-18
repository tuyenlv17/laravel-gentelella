@extends('template')

@include('admin.options.partials.common')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    Cập nhật tham số 
                </div>
                <div class="actions">
                    <a href="{{ url('/admin/options') }}" class="btn default">Thêm tham số</a>
                </div>
            </div>
            <div class="portlet-body">
                @include('components.common.alert')

                {{ Form::model($option, array('route' => ['admin.options.update', $option->id], 'method' => 'PATCH', 'id' => 'option-form')) }}
                @include('admin.options.partials.form')
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        @include('admin.options.partials.table')
    </div>
</div>
@endsection

