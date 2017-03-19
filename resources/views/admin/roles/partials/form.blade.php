<div class="row">
    <div class="col-sm-5">
        {{ Form::ctText('name', 'Mã nhóm', null, [], true) }}
    </div>
    <div class="col-sm-7">
        {{ Form::ctText('display_name', 'Tên nhóm', null, [], true) }}
    </div>
    <div class="col-sm-12">
        {{ Form::ctText('description', 'Mô tả', null, [], false) }}
    </div>
    <div class="col-sm-12">
        {{ Form::ctText('default_url', 'Default page', null, [], true) }}
    </div>
    <div class="col-sm-12">
        <label>{{trans('admin.Permission')}}</label>
    </div>
    <div class="col-md-12">        
        @foreach($permission_group as $group_name=>$permissions)
            <div class="panel-group accordion" id="accordion-permission-{{$group_name}}">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" aria-expanded="false" data-parent="#accordion-permission-{{$group_name}}" href="#collapse_permission-{{$group_name}}"> {{$group_name}} </a>
                        </h4>
                    </div>
                    <div id="collapse_permission-{{$group_name}}" class="panel-collapse in">
                        <div class="panel-body">
                            <div class="mt-checkbox-list">
                                @foreach($permissions as $permission)
                                <div class="col-md-4">
                                    <label class="mt-checkbox mt-checkbox-outline">
                                        @if(isset($editForm))
                                        {{ Form::checkbox('permission[]', $permission->id, in_array($permission->id, $current_permisisons) ? true : false, array('class' => 'name')) }}
                                        @else
                                        {{ Form::checkbox('permission[]', $permission->id, false, array('class' => 'name')) }}                      
                                        @endif                                    
                                        {{ $permission->display_name }}
                                        <span></span>
                                    </label>
                                </div>                        
                                @endforeach
                            </div>                        
                        </div>
                    </div>
                </div>            
            </div>
        @endforeach
    </div>
    <div class="col-md-12">
        {{ Form::submit('Submit', array('class' => 'btn btn-success')) }}
        {{ Form::reset('Cancel', array('class' => 'btn btn-default')) }}
    </div>                    
</div>