@props(['title' => ''])

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
    <x-dialog id="logout-dialog" title="Se déconnecter ?" description="Vous devrez vous reconnecter pour retrouver vos animaux.">
        <form method="post" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn--danger btn--block">Se déconnecter</button>
        </form>
        <button type="button" class="btn btn--ghost btn--block" data-dialog-close>Annuler</button>
    </x-dialog>

</x-layouts.base>
