@extends('template')

@include('admin.permissions.partials.common')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    Cập nhật quyền người dùng
                </div>
                <div class="actions">
                    <a href="{{ url('/admin/permissions') }}" class="btn default">Thêm quyền người dùng</a>
                </div>
            </div>
            <div class="portlet-body">
                @include('components.common.alert')

                {{ Form::model($permission, array('route' => ['admin.permissions.update', $permission->id], 'method' => 'PATCH', 'id' => 'permission-form')) }}
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

