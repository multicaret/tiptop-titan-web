@php
    $errorName = $name;
    if (\Str::is('*[*', $name)) {
        $errorName = str_replace('[', '.', str_replace(']', '', $name));
    }
    if(!isset($labelPostfix) || empty($labelPostfix)) {
        $labelPostfix = '';
    }
    if(isset($attributes) && in_array('required',$attributes,true)) {
        $labelPostfix = '<b class="text-danger">*</b>';
    }
@endphp

<div class="form-group {{ $errors->has($errorName) ? ' has-error has-danger' : '' }} {{ $class ?? null }}">
    @if(isset($label) && ! in_array($type, ['checkbox', 'radio']))
        {{ Form::label($name, $label .' &nbsp;'. $labelPostfix, ['class' => 'control-label form-control-label'],false) }}
    @endif

    @if(isset($inputGroup))
        <div class="input-group">
            @endif

            @switch($type)
                @case('password')
                @case('file')
                {{ Form::$type($name, array_merge(['class' => 'form-control'], $attributes ?? [])) }}
                @break

                @case('editor')
                <div class="editor quill-container" data-name="{{ $name }}">{!! $value ?? old($name) !!}</div>
                {{ Form::textarea($name, $value ?? null, array_merge(['class' => 'd-none'], $attributes ?? [])) }}
                @break

                @case('datepicker')
                <div class="input-group date datepicker">
                    {{ Form::text($name, $value ?? null, array_merge(['class' => 'form-control has-value'], $attributes ?? [])) }}
                    <span class="input-group-addon input-group-append border-left">
                          <span class="mdi mdi-calendar input-group-text"></span>
                    </span>
                </div>
                @break

                @case('datetime-local')
                <div class="input-group datetime-local">
                    {{ Form::datetimeLocal($name, $value ?? null, array_merge(['class' => 'form-control has-value'], $attributes ?? [])) }}
                </div>
                @break

                @case('select')
                {{ Form::select($name,
                    $options ?? [],
                    $selected ?? null,
                    array_merge(['class' => 'form-control has-value'], $attributes ?? []),
                    $optionsAttributes ?? [],
                    $optgroupsAttributes ?? []
                ) }}
                @break

                @case('checkbox')
                @case('radio')
                <div class="form-check">
                    {!! Form::$type($name, $value ?? null, $checked ?? null,
                        array_merge(['class' => 'form-check-input', 'id' => $name], $attributes ?? [])
                    ); !!}
                    <div class="form-check-label">
                        {{ Form::label($name, isset($label) ? $label .' &nbsp;' . $labelPostfix : ' ' . $labelPostfix, ['class' => '']) }}
                    </div>
                </div>

                @break

                @default
                {{ Form::$type($name, $value ?? null, array_merge(['class' => 'form-control has-value'], $attributes ?? [])) }}
            @endswitch

            @if(isset($inputGroup))
                <div class="input-group-prepend">
                    <span class="input-group-text">{{ $inputGroup }}</span>
                </div>
        </div>
    @endif

    @if($slot)
        <small class="form-text form-control-feedback">{{ $slot }}</small>
    @endif

    @if($errors->has($errorName))
        <small class="form-text text-danger form-control-feedback">
            {{ $errors->first($errorName) }}
        </small>
    @endif
</div>
