@extends('layouts.base')

@section('content')
    <p>Hello World ! </p>

    @if( auth()->user() )
        <p>Bienvenue {{ auth()->user()->name }}</p>

        <form method="post" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Se déconnecter</button>
        </form>
    @else
        <p>User pas connecté</p>

        <p><a href="{{ route('login') }}">Se connecter</a></p>
        <p><a href="{{ route('register') }}">Créer un compte</a></p>
    @endif
@endsection
