@props(['items' => []])

<nav {{ $attributes->class(['sidebar']) }} aria-label="Navigation principale">
    <ul class="sidebar__nav">
        @foreach ($items as $item)
            <li class="sidebar__nav-item">
                <a href="{{ $item['href'] }}"
                   class="sidebar__nav-link @if($item['active'] ?? false) sidebar__nav-link--active @endif">
                    {{ $item['label'] }}
                </a>
            </li>
        @endforeach
    </ul>

    @isset($footer)
        <div class="sidebar__footer">
            {{ $footer }}
        </div>
    @endisset
</nav>
