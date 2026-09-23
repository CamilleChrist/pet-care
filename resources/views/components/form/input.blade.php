@props([
    'name',
    'label',
    'type' => 'text',
    'icon' => null,
    'hint' => null,
    'required' => false,
    'rows' => null, // rend un <textarea> de N lignes
])

@php
    $value = old($name, $attributes->get('value'));

    $hasError = $errors->has($name);

    $control = $attributes
        ->except('value')
        ->class([
            'input__control',
            'input__control--error' => $hasError,
        ])
        ->merge([
            'aria-invalid' => $hasError ? 'true' : null,
            'aria-describedby' => $hasError ? "{$name}-error" : null,
        ]);
@endphp

<div class="field">
    <label class="field__label" for="{{ $name }}">
        {{ $label }}
        @if ($required)
            <span class="field__required" aria-hidden="true">*</span>
        @endif
    </label>

    <div @class(['input', 'input--with-icon' => $icon])>
        @if ($icon)
            <x-ui.icon :name="$icon" class="input__icon" />
        @endif

        @if ($rows)
            <textarea {{ $control }} id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}" @required($required)>{{ $value }}</textarea>
        @else
            <input
                {{ $control }}
                id="{{ $name }}"
                name="{{ $name }}"
                type="{{ $type }}"
                @if ($type !== 'password') value="{{ $value }}" @endif
                @required($required)
            >
        @endif
    </div>

    @error($name)
        <p class="field__error" id="{{ $name }}-error">{{ $message }}</p>
    @else
        @if ($hint)
            <p class="field__hint">{{ $hint }}</p>
        @endif
    @enderror
</div>
