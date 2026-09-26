@props([
    'title' => '',
    'description' => '',
    'back' => null, // URL du bouton retour, affiché sous md uniquement (topbar mobile)
])

@php
    $navItems = [
        [
            'label' => 'Utilisateurs',
            'icon' => 'user',
            'href' => route('admin.users.index'),
            'active' => request()->routeIs('admin.users.*'),
        ],
        [
            'label' => "Retour à l'app",
            'icon' => 'arrow-left',
            'href' => route('dashboard'),
            'active' => false,
        ],
    ];
@endphp

<x-layouts.base :title="'Admin - '.$title" class="app">

    <x-layouts.nav.sidebar :items="$navItems" :pets="collect()" />

    <main class="admin-main">
        <header class="page-header">
            @if ($back)
                <a href="{{ $back }}" class="page-header__back hidden-md-up" aria-label="Retour">
                    <x-ui.icon name="arrow-left"/>
                </a>
            @endif
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

    <x-ui.dialog id="logout-dialog" title="Se déconnecter ?" description="Vous devrez vous reconnecter pour revenir au back-office.">
        <form method="post" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn--danger btn--block">Se déconnecter</button>
        </form>
        <button type="button" class="btn btn--ghost btn--block" data-dialog-close>Annuler</button>
    </x-ui.dialog>

</x-layouts.base>
