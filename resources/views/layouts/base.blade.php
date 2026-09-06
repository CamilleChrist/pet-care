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

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    @yield('content')

</body>
</html>
