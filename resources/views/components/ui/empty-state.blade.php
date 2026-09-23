@props(['icon' => 'paw-print', 'title', 'description' => null])

<div {{ $attributes->class(['empty-state']) }}>
    <span class="empty-state__icon"><x-ui.icon :name="$icon"/></span>

    <h2 class="empty-state__title">{{ $title }}</h2>

    @if ($description)
        <p class="empty-state__description">{{ $description }}</p>
    @endif

    {{ $slot }}
</div>
