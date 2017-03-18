<div class="row">
    {{Form::hidden('is-adding-user',isset($current_role) ? 'false' : 'true', array('id' => 'is-adding-user'))}}
    <div class="col-sm-12">
        {{ Form::bsText('name', 'Tên người dùng', null, [], true) }}
    </div>
    <div class="col-sm-12">
        {{ Form::bsText('email', 'Địa chỉ mail', null, [], true) }}
    </div>
    <div class="col-sm-12">
        {{ Form::bsPassword('password', 'Mật khẩu', null, [], isset($current_role) ? false : true) }}
    </div>
    <div class="col-sm-12">
        {{ Form::bsPassword('password_confirmation', 'Nhập lại mật khẩu', null, [], isset($current_role) ? false : true) }}
    </div>
    <div class="col-sm-12">
        {{ Form::bsSelect('role', 'Chọ vai trò', $roles, isset($current_role) ? $current_role : NULL, null, [], true) }}
    </div>
    <div class="col-md-12">
        {{ Form::submit('Submit', array('class' => 'btn btn-success')) }}
        {{ Form::reset('Cancel', array('class' => 'btn btn-default')) }}
    </div>                    
</div>