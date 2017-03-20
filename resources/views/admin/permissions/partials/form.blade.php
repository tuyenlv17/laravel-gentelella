<div class="row">
    <div class="col-sm-5">
        {{ Form::ctText('name', 'Mã quyền', null, [], true) }}
    </div>
    <div class="col-sm-7">
        {{ Form::ctText('display_name', 'Tên quyền', null, [], true) }}
    </div>
    <div class="col-sm-12">
        {{ Form::ctText('description', 'Mô tả', null, [], false) }}
    </div>    
    <div class="col-sm-12">
        {{ Form::ctSelect('group', 'Group', $groups, isset($current_group) ? $current_group : NULL, null, [], true) }}
    </div>
    <div class="col-md-12">
        {{ Form::submit('Submit', array('class' => 'btn btn-success')) }}
        {{ Form::reset('Cancel', array('class' => 'btn btn-default')) }}
    </div>                    
</div>