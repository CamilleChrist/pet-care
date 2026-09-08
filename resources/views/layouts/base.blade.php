<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>App Name - @yield('title')</title>

    @fonts

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="max-w-2xl mx-auto p-4">

<header>
    @if( auth()->user() )
        <nav>
            <ul class="flex items-center gap-4 mb-4">
                <li>
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-blue-600">Se déconnecter</button>
                    </form>
                </li>
                <li><a class="text-blue-600 hover:underline" href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a class="text-blue-600 hover:underline" href="{{ route('pets.create') }}">Ajouter un pet</a></li>
                <li><a class="text-blue-600 hover:underline" href="{{ route('pets.index') }}">Liste des pet</a></li>
            </ul>
        </nav>
    @endif
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
