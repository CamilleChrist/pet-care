<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>App Name - @yield('title')</title>

    @fonts

    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>

<body class="max-w-2xl mx-auto p-4">

<header>
    <nav>
        <ul class="flex items-center gap-4 mb-4">
            <li><a class="text-blue-600 hover:underline" href="{{ route('welcome') }}">Accueil</a></li>
            @if( auth()->user() )
                <li><a class="text-blue-600 hover:underline" href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a class="text-blue-600 hover:underline" href="{{ route('pets.create') }}">Ajouter un pet</a></li>
                <li><a class="text-blue-600 hover:underline" href="{{ route('pets.index') }}">Liste des pet</a></li>
                <li>
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-blue-600">Se déconnecter</button>
                    </form>
                </li>
            @else
                <li><a class="text-blue-600 hover:underline" href="{{ route('login') }}">Se connecter</a></li>
                <li><a class="text-blue-600 hover:underline" href="{{ route('register') }}">Créer un compte</a></li>
            @endif
        </ul>
    </nav>
</header>

@if (session('success'))
    <p class="mb-4">{{ session('success') }}</p>
@endif

@if (session('status'))
    <p class="mb-4">{{ session('status') }}</p>
@endif

@yield('content')

</body>
</html>
