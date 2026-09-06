@extends('layouts.base')

@section('content')

    <h1>Réinitialiser le mot de passe</h1>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        <input name="token" type="hidden" value="{{ $token }}">

        <label>
            Email :
            <input name="email" type="email" value="{{ old('email', $email) }}">
        </label>

        <label>
            Mot de passe
            <input name="password" type="password">
        </label>

        <label>
            Confirmer le mot de passe
            <input name="password_confirmation" type="password">
        </label>

        <button type="submit">Réinitialiser le mot de passe</button>
    </form>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
@endsection
