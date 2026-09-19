@props([
    'name',
    'label',
    'checked' => false,
])

<label class="switch">
    <span class="switch__label">{{ $label }}</span>
    <input {{ $attributes }} type="checkbox" role="switch" name="{{ $name }}" value="1" @checked(old($name, $checked))>
    <span class="switch__track"><span class="switch__knob"></span></span>
</label>
