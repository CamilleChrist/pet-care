@props([
    'tagTitle' => 'h2',
    'title',
    'description' => null,
])

<div {{ $attributes->class(['card']) }}>
    <header class="card__header">
        <{{ $tagTitle }} class="card__title">{{ $title }}</{{ $tagTitle }}>

        @if ($description)
            <p class="card__description">{{ $description }}</p>
        @endif
    </header>

    {{ $slot }}
</div>
