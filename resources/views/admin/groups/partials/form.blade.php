<div class="row">
    <div class="col-sm-5">
        {{ Form::bsText('name', 'Name', null, [], true) }}
    </div>
    <div class="col-sm-7">
        {{ Form::bsText('display_name', 'Display name', null, [], true) }}
    </div>
    <div class="col-sm-12">
        {{ Form::bsText('description', 'Description', null, [], false) }}
    </div>    
    <div class="col-md-12">
        {{ Form::submit('Submit', array('class' => 'btn btn-success')) }}
        {{ Form::reset('Cancel', array('class' => 'btn btn-default')) }}
    </div>                    
</div>