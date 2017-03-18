@extends('template')

@include('admin.groups.partials.common')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    {{trans('Group permission')}}
                </div>
            </div>
            <div class="portlet-body">
                @include('components.common.alert')

                {{ Form::open(array('url' => 'admin/groups', 'id' => 'group-form')) }}
                @include('admin.groups.partials.form')
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        @include('admin.groups.partials.table')
    </div>
</div>
@endsection

