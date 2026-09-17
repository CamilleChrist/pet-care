@props(['items' => []])

<nav {{ $attributes->class(['bottom-nav']) }} aria-label="Navigation principale">
    @foreach ($items as $item)
        <a href="{{ $item['href'] }}"
           class="bottom-nav__item @if($item['active'] ?? false) bottom-nav__item--active @endif">
            {{ $item['label'] }}
        </a>
    @endforeach
</nav>
