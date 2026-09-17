@props(['title' => ''])

@php
    $navItems = [
        ['label' => 'Accueil', 'href' => route('welcome'), 'active' => request()->routeIs('welcome')],
        ['label' => 'Dashboard', 'href' => route('dashboard'), 'active' => request()->routeIs('dashboard')],
        ['label' => 'Ajouter un pet', 'href' => route('pets.create'), 'active' => request()->routeIs('pets.create')],
        ['label' => 'Liste des pets', 'href' => route('pets.index'), 'active' => request()->routeIs('pets.*')],
    ];
@endphp

<x-layouts.base :title="$title" class="app">

    <x-layouts.nav.sidebar :items="$navItems">
        <x-slot:footer>
            <form method="post" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Se déconnecter</button>
            </form>
        </x-slot:footer>
    </x-layouts.nav.sidebar>

    <main class="app__main">
        @if (session('success'))
            <p class="mb-4">{{ session('success') }}</p>
        @endif

        @if (session('status'))
            <p class="mb-4">{{ session('status') }}</p>
        @endif

        {{ $slot }}
    </main>

    <x-layouts.nav.bottom-nav :items="$navItems" />

</x-layouts.base>
