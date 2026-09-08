<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>App Name - @yield('title')</title>

    @fonts

    <!-- Styles / Scripts -->
    {{--    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))--}}
    {{--        @vite(['resources/css/app.css', 'resources/js/app.js'])--}}
    {{--    @endif--}}
</head>

<body>

<header>
    @if( auth()->user() )
        <nav>
            <ul>
                <li>
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Se déconnecter</button>
                    </form>
                </li>
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('pets.create') }}">Ajouter un pet</a></li>
                <li><a href="{{ route('pets.index') }}">Liste des pet</a></li>
            </ul>
        </nav>
    @endif
</header>

@if (session('success'))
    <p>{{ session('success') }}</p>
@endif

@if (session('status'))
    <p>{{ session('status') }}</p>
@endif

@yield('content')

</body>
</html>
