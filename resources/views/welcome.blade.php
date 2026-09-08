@extends('layouts.base')

@section('content')
    <h1 class="text-xl font-bold mb-4">Page d'accueil </h1>

    <p class="mb-4">Ceci est la page d'accueil de mon site !</p>

    @if( auth()->user() )
        <nav>
            <ul class="flex flex-col gap-2">
                <li><a class="text-blue-600 hover:underline" href="{{ route('dashboard') }}">Aller au dashboard</a></li>
                <li>
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-blue-600">Se déconnecter</button>
                    </form>
                </li>
            </ul>
        </nav>
    @else
        <p class="mb-2">User pas connecté</p>

        <p class="mb-2"><a class="text-blue-600 hover:underline" href="{{ route('login') }}">Se connecter</a></p>
        <p class="mb-2"><a class="text-blue-600 hover:underline" href="{{ route('register') }}">Créer un compte</a></p>
    @endif
@endsection
