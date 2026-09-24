@props([
    'title' => '',
    'description' => '',
    'noBreadcrumb' => false,
    'back' => null, // URL du bouton retour, affiché sous md uniquement (topbar mobile)
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
        [
            'label' => 'Profil',
            'icon' => 'user',
            'href' => route('profile.edit'),
            'active' => request()->routeIs('profile.*')
        ],
    ];
@endphp

<x-layouts.base :title="$title" class="app">

        <x-layouts.nav.sidebar :items="$navItems" :pets="$pets" />

    <main>
        @if(!$noBreadcrumb)
            <x-nav.breadcrumb />
        @endif

        <header class="page-header">
            @if ($back)
                <a href="{{ $back }}" class="page-header__back hidden-md-up" aria-label="Retour">
                    <x-ui.icon name="arrow-left"/>
                </a>
            @endif
            @isset($avatar)
                {{ $avatar }}
            @endisset
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
            <x-ui.alert tone="success">
                <strong>{{ session('success') }}</strong>
            </x-ui.alert>
        @endif

        @if (session('status'))
            <x-ui.alert tone="info">
                <strong>{{ session('status') }}</strong>
            </x-ui.alert>
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
