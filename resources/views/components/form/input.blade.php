@props([
    'name',
    'label',
    'type' => 'text',
    'icon' => null,
    'hint' => null,
    'required' => false,
    'options' => null, // [valeur => libellé] : rend un <select>
    'rows' => null,    // rend un <textarea> de N lignes
])

@php
    $value = old($name, $attributes->get('value'));
    $control = $attributes->except('value')
        ->class(['input__control', 'input__control--error' => $errors->has($name)])
        ->merge($errors->has($name) ? ['aria-invalid' => 'true', 'aria-describedby' => $name.'-error'] : []);
@endphp

<div class="field">
    <label class="field__label" for="{{ $name }}">
        {{ $label }}
        @if ($required)
            <span class="field__required" aria-hidden="true">*</span>
        @endif
    </label>

    <div @class(['input', 'input--with-icon' => $icon, 'input--select' => $options !== null])>
        @if ($icon)
            <x-ui.icon :name="$icon" class="input__icon" />
        @endif

        @if ($options !== null)
            <select {{ $control }} id="{{ $name }}" name="{{ $name }}" @required($required)>
                @foreach ($options as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
                @endforeach
            </select>

            {{-- Le reset supprime la flèche native (appearance: none). --}}
            <x-ui.icon name="chevron-down" class="input__chevron" />
        @elseif ($rows)
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
