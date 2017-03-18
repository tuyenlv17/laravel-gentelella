@extends('template')

@include('admin.users.partials.common')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    Thêm người dùng
                </div>
            </div>
            <div class="portlet-body">
                @include('components.common.alert')

                {{ Form::open(array('url' => 'admin/users', 'id' => 'user-form')) }}
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

