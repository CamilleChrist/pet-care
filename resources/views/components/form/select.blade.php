@props([
    'name',
    'label',
    'options', // [valeur => libellé]
    'icon' => null,
    'hint' => null,
    'required' => false,
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

    <div @class(['input', 'input--select', 'input--with-icon' => $icon])>
        @if ($icon)
            <x-ui.icon :name="$icon" class="input__icon" />
        @endif

        <select {{ $control }} id="{{ $name }}" name="{{ $name }}" @required($required)>
            @foreach ($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
            @endforeach
        </select>

        {{-- Le reset supprime la flèche native (appearance: none). --}}
        <x-ui.icon name="chevron-down" class="input__chevron" />
    </div>

    @error($name)
        <p class="field__error" id="{{ $name }}-error">{{ $message }}</p>
    @else
        @if ($hint)
            <p class="field__hint">{{ $hint }}</p>
        @endif
    @enderror
</div>
