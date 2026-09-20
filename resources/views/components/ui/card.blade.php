@props([
    'tagTitle' => 'h3',
    'subtitle' => null,
    'title' => null,
    'description' => null,
])

<div {{ $attributes->class(['card']) }}>
    @if ($subtitle || $title || $description)
        <header class="card__header">
            @if ($subtitle)
                <p class="card__subtitle">{{ $subtitle }}</p>
            @endif

            @if ($title)
                <{{ $tagTitle }} class="card__title">{{ $title }}</{{ $tagTitle }}>
            @endif

            @if ($description)
                <p class="card__description">{{ $description }}</p>
            @endif
        </header>
    @endif

    {{ $slot }}
</div>
