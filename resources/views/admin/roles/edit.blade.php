@extends('template')

@include('admin.roles.partials.common')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    Cập nhật nhóm người dùng
                </div>
                <div class="actions">
                    <a href="{{ url('/admin/roles') }}" class="btn default">Thêm nhóm người dùng</a>
                </div>
            </div>
            <div class="portlet-body">
                @include('components.common.alert')

                {{ Form::model($role, array('route' => ['admin.roles.update', $role->id], 'method' => 'PATCH', 'id' => 'role-form')) }}
                @include('admin.roles.partials.form')
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        @include('admin.roles.partials.table')
    </div>
</div>
@endsection

