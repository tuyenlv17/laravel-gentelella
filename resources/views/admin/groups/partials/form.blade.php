<div class="row">
    <div class="col-sm-5">
        {{ Form::ctText('name', 'Name', null, [], true) }}
    </div>
    <div class="col-sm-7">
        {{ Form::ctText('display_name', 'Display name', null, [], true) }}
    </div>
    <div class="col-sm-12">
        {{ Form::ctText('description', 'Description', null, [], false) }}
    </div>    
    <div class="col-md-12">
        {{ Form::submit('Submit', array('class' => 'btn btn-success')) }}
        {{ Form::reset('Cancel', array('class' => 'btn btn-default')) }}
    </div>                    
</div>