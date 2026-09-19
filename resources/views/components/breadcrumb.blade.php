<nav class="breadcrumb" aria-label="Fil d'Ariane">
    <ol class="breadcrumb-list">
        <li class="breadcrumb-list__item">

            @if ($items)
                <a href="{{ route('dashboard') }}">Accueil</a>
            @else
                <span aria-current="page">Accueil</span>
            @endif

        </li>

        @foreach ($items as $item)
            <li class="breadcrumb-list__item">

                @if (!$loop->last && $item['url'])
                    <a href="{{ $item['url'] }}">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span @if ($loop->last) aria-current="page" @endif>
                        {{ $item['label'] }}
                    </span>
                @endif

            </li>
        @endforeach
    </ol>
</nav>
