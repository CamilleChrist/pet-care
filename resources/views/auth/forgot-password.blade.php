@extends('layouts.base')

@section('content')

    <h1>Mot de passe oublié</h1>

    <form method="post" action="{{ route('password.email') }}">
        @csrf
        <label>
            Email :
            <input name="email" type="email" value="{{ old('email') }}">
        </label>

        <button type="submit">Envoyer le lien de réinitialisation</button>
    </form>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
@endsection
