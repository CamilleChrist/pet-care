@props(['items' => []])

<nav {{ $attributes->class(['bottom-nav']) }} aria-label="Navigation principale">
    @foreach ($items as $item)
        <a href="{{ $item['href'] }}" class="bottom-nav__item" @if ($item['active'] ?? false) aria-current="page" @endif>
            <span class="bottom-nav__icon"><x-icon :name="$item['icon']"/></span>
            {{ $item['label'] }}
        </a>
    @endforeach
</nav>
