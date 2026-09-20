@props([
    'name',
    'label',
    'type' => 'text',
    'icon' => null,
    'hint' => null,
    'required' => false,
])

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
        <input
            {{ $attributes->except('value')->class(['input__control', 'input__control--error' => $errors->has($name)]) }}
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            @if ($type !== 'password') value="{{ old($name, $attributes->get('value')) }}" @endif
            @required($required)
            @if ($errors->has($name)) aria-invalid="true" aria-describedby="{{ $name }}-error" @endif
        >
    </div>

    @error($name)
        <p class="field__error" id="{{ $name }}-error">{{ $message }}</p>
    @else
        @if ($hint)
            <p class="field__hint">{{ $hint }}</p>
        @endif
    @enderror
</div>
