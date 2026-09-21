@props(['items' => [], 'pets' => []])

<nav {{ $attributes->class(['sidebar']) }} aria-label="Navigation principale">
    <x-ui.logo class="sidebar__brand"/>

    <ul class="sidebar__group">
        @foreach ($items as $item)
            <li>
                <a href="{{ $item['href'] }}" class="sidebar__item" @if ($item['active'] ?? false) aria-current="page" @endif>
                    <x-ui.icon :name="$item['icon']" class="sidebar__icon"/>
                    <span class="sidebar__label">{{ $item['label'] }}</span>
                    @isset($item['count'])
                        <span class="sidebar__count">{{ $item['count'] }}</span>
                    @endisset
                </a>
            </li>
        @endforeach
    </ul>

    @if ($pets->isNotEmpty())
        <ul class="sidebar__group">
            <li class="sidebar__group-label">Mes animaux</li>
            @foreach ($pets as $pet)
                <li>
                    <a href="{{ route('pets.show', $pet) }}" class="sidebar__item" @if (request()->route('pet')?->is($pet)) aria-current="page" @endif>
                        <x-pet.avatar :pet="$pet" size="xs" />
                        <span class="sidebar__label">{{ $pet->name }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif

    <div class="sidebar__footer">
        <button type="button" class="sidebar__toggle" aria-label="Réduire le menu" aria-expanded="true">
            <x-ui.icon name="panel-left" class="sidebar__icon"/>
        </button>
        <button type="button" class="sidebar__item" data-dialog-open="logout-dialog">
            <x-ui.icon name="log-out" class="sidebar__icon"/>
            <span class="sidebar__label">Se déconnecter</span>
        </button>
    </div>
</nav>
