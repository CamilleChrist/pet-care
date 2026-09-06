@extends('layouts.base')

@section('content')

    <h1>Créer un compte</h1>

    <form method="post" action="">
        @csrf
        <label>
            Nom :
            <input name="name" type="text">
        </label>

        <label>
            Email :
            <input name="email" type="email">
        </label>

        <label>
            Mot de passe
            <input name="password" type="password">
        </label>

        <label>
            Confirmer le mot de passe
            <input name="password_confirmation" type="password">
        </label>

        <button type="submit">Créer un compte</button>
    </form>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
@endsection
