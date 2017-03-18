<div class="form-group">    
    @if($required)
    {!! Form::rawLabel($name, $title . '<span class="required">*</span>', ['class' => 'control-label']) !!}
    @else
    {{ Form::label($name, $title, ['class' => 'control-label']) }}
    @endif 
    {{ Form::select($name, $values, $current_value,  array_merge(['class' => 'form-control'], $attributes)) }}
</div>