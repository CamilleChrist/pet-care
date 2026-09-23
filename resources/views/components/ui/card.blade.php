@props([
    'tagTitle' => 'h3',
    'subtitle' => null,
    'title' => null,
    'description' => null,
])

<div {{ $attributes->class(['card']) }}>
    @if ($subtitle || $title || $description || isset($actions))
        <header class="card__header">
            <div class="card__heading">
                @if ($subtitle)
                    <p class="card__subtitle">{{ $subtitle }}</p>
                @endif

                @if ($title)
                    <{{ $tagTitle }} class="card__title">{{ $title }}</{{ $tagTitle }}>
                @endif

                @if ($description)
                    <p class="card__description">{{ $description }}</p>
                @endif
            </div>

            @isset($actions)
                {{ $actions }}
            @endisset
        </header>
    @endif

    {{ $slot }}
</div>
