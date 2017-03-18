<div class="form-group">    
    @if($required)
    {!! Form::rawLabel($name, $title . '<span class="required">*</span>', ['class' => 'control-label']) !!}
    @else
    {{ Form::label($name, $title, ['class' => 'control-label']) }}
    @endif 
    {{ Form::password($name, array('class' => 'form-control')) }}
</div>