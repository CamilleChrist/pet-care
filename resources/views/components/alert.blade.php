@props(['tone' => 'info']) {{-- info | success | danger --}}

@php
    $icon = ['info' => 'info', 'success' => 'circle-check', 'danger' => 'circle-alert'][$tone];
@endphp

<p {{ $attributes->class(['alert', "alert--$tone"]) }} role="{{ $tone === 'danger' ? 'alert' : 'status' }}">
    <x-icon :name="$icon" class="alert__icon" />
    <span>{{ $slot }}</span>
</p>
