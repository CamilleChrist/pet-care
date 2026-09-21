@props(['pet', 'size' => 'sm']) {{-- xs (32px) | sm (44px) | md (64px) | lg (116px) --}}

@php
    $species = $pet->breed?->species;
@endphp

<span {{ $attributes->class(['pet-avatar', "pet-avatar--$size", $species ? "pet-avatar--$species" : '']) }} role="img" aria-label="Photo de {{ $pet->name }}">
    @if ($pet->photo_path)
        <img src="{{ $pet->photoUrl() }}" alt="">
    @else
        <x-ui.icon :name="$species ?? 'paw-print'" />
    @endif
</span>
