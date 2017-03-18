@extends('template')

@include('admin.groups.partials.common')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    {{trans('permission_assign.Edit group')}}
                </div>
                <div class="actions">
                    <a href="{{ url('/admin/groups') }}" class="btn default">{{trans('permission_assign.Add group')}}</a>
                </div>
            </div>
            <div class="portlet-body">
                @include('components.common.alert')

                {{ Form::model($group, array('route' => ['admin.groups.update', $group->id], 'method' => 'PATCH', 'id' => 'group-form')) }}
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

