@extends('template')

@include('admin.users.partials.common')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    Cập nhật nhóm người dùng
                </div>
                <div class="actions">
                    <a href="{{ url('/admin/users') }}" class="btn default">Thêm nhóm người dùng</a>
                </div>
            </div>
            <div class="portlet-body">
                @include('components.common.alert')

                {{ Form::model($user, array('route' => ['admin.users.update', $user->id], 'method' => 'PATCH', 'id' => 'user-form')) }}
                @include('admin.users.partials.form')
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        @include('admin.users.partials.table')
    </div>
</div>
@endsection

