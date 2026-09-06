@extends('layouts.base')

@section('content')
    <h1>Page d'accueil </h1>

    <p>Ceci est la page d'accueil de mon site !</p>

    @if( auth()->user() )
        <nav>
            <ul>
                <li><a href="{{ route('dashboard') }}">Aller au dashboard</a></li>
                <li>
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Se déconnecter</button>
                    </form>
                </li>
            </ul>
        </nav>
    @else
        <p>User pas connecté</p>

        <p><a href="{{ route('login') }}">Se connecter</a></p>
        <p><a href="{{ route('register') }}">Créer un compte</a></p>
    @endif
@endsection
