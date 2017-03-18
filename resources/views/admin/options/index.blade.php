@extends('template')

@include('admin.options.partials.common')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    Thêm tham số 
                </div>
            </div>
            <div class="portlet-body">
                @include('components.common.alert')

                {{ Form::open(array('url' => 'admin/options', 'id' => 'option-form')) }}
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

