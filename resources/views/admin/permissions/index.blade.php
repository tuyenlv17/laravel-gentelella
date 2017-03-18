@extends('template')

@include('admin.permissions.partials.common')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    Thêm quyền người dùng
                </div>
            </div>
            <div class="portlet-body">
                @include('components.common.alert')

                {{ Form::open(array('url' => 'admin/permissions', 'id' => 'permission-form')) }}
                @include('admin.permissions.partials.form')
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        @include('admin.permissions.partials.table')
    </div>
</div>
@endsection

