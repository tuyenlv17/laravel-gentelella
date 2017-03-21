<div class="form-group">    
    @if($required)
    {!! Form::rawLabel($name, $title . '<span class="required">*</span>', ['class' => 'control-label']) !!}
    @else
    {{ Form::label($name, $title, ['class' => 'control-label']) }}
    @endif 
    <?php
        if (!isset($attributes['class'])) {
            $attributes['class'] = '';
        }
        $attributes['class'] .= ' form-control';
    ?>
    {{ Form::textarea($name, $value, $attributes) }}
</div>