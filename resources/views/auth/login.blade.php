@extends('layouts.base')

@section('content')

    <h1 class="text-xl font-bold mb-4">Se connecter !</h1>
    <form method="post" class="flex flex-col gap-4">
        @csrf
        <label class="flex flex-col gap-1">
            Email :
            <input name="email" type="email" class="border p-2">
        </label>

        <label class="flex flex-col gap-1">
            Mot de passe
            <input name="password" type="password" class="border p-2">
        </label>

        <button type="submit" class="bg-blue-600 text-white p-2 self-start">Se connecter</button>
    </form>

    @if ($errors->any())
        <ul class="mt-4 flex flex-col gap-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <a class="mt-4 inline-block text-blue-600 hover:underline" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
@endsection
