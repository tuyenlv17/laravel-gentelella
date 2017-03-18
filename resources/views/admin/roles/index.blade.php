@extends('template')

@include('admin.roles.partials.common')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    Thêm nhóm người dùng
                </div>
            </div>
            <div class="portlet-body">
                @include('components.common.alert')

                {{ Form::open(array('url' => 'admin/roles', 'id' => 'role-form')) }}
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

