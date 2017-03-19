<div class="row">
    <div class="col-sm-5">
        {{ Form::ctText('key', 'Mã tham số', null, [], true) }}
    </div>
    <div class="col-sm-7">
        {{ Form::ctText('value', 'Giá trị', null, [], true) }}
    </div>
    <div class="col-md-12">
        {{ Form::submit('Submit', array('class' => 'btn btn-success')) }}
        {{ Form::reset('Cancel', array('class' => 'btn btn-default')) }}
    </div>                    
</div>