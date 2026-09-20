@props([
    'title' => '',
    'description' => ''
])

@php
    $pets = auth()->user()->pets;
    $navItems = [
        [
            'label' => 'Accueil',
            'icon' => 'house',
            'href' => route('dashboard'),
            'active' => request()->routeIs('dashboard')
        ],
        [
            'label' => 'Animaux',
            'icon' => 'paw-print',
            'count' => $pets->count(),
            'href' => route('pets.index'),
            'active' => request()->routeIs('pets.index', 'pets.create')
        ],
    ];
@endphp

<x-layouts.base :title="$title" class="app">

    <x-layouts.nav.sidebar :items="$navItems" :pets="$pets" />

    <main>
        <x-nav.breadcrumb />

        <header class="page-header">
            <div class="page-header__heading">
                <h1 class="page-header__title">{{ $title }}</h1>
                @if ($description)
                    <p class="page-header__description">{{ $description }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="page-header__actions">{{ $actions }}</div>
            @endisset
        </header>

        @if (session('success'))
            <p class="mb-4">{{ session('success') }}</p>
        @endif

        @if (session('status'))
            <p class="mb-4">{{ session('status') }}</p>
        @endif

        {{ $slot }}
    </main>

    <x-layouts.nav.bottom-nav :items="$navItems" />

    {{-- Hors de la sidebar : elle est masquée sous md, le dialog doit rester ouvrable partout. --}}
    <x-ui.dialog id="logout-dialog" title="Se déconnecter ?" description="Vous devrez vous reconnecter pour retrouver vos animaux.">
        <form method="post" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn--danger btn--block">Se déconnecter</button>
        </form>
        <button type="button" class="btn btn--ghost btn--block" data-dialog-close>Annuler</button>
    </x-ui.dialog>

</x-layouts.base>
