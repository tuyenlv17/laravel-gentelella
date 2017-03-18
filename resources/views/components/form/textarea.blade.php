<div class="form-group">    
    @if($required)
    {!! Form::rawLabel($name, $title . '<span class="required">*</span>', ['class' => 'control-label']) !!}
    @else
    {{ Form::label($name, $title, ['class' => 'control-label']) }}
    @endif 
    {{ Form::textarea($name, $value, array_merge(['class' => 'form-control'], $attributes)) }}
</div>