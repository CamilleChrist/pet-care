@extends('layouts.base')

@section('content')

    <h1>Se connecter !</h1>
    <form method="post">
        @csrf
        <label>
            Email :
            <input name="email" type="email">
        </label>

        <label>
            Mot de passe
            <input name="password" type="password">
        </label>

        <button type="submit">Se connecter</button>
    </form>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
@endsection
