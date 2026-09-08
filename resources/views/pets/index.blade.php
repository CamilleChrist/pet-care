@extends('layouts.base')

@section('content')

    <h1 class="text-xl font-bold mb-4">Mes Pet</h1>

    <p class="mb-4">Liste des mes animaux</p>

    <ul class="flex flex-col gap-2">
        @foreach($pets as $pet)
            <li>
                <a class="text-blue-600 hover:underline" href="{{ route('pets.show', [$pet->id]) }}">Voir la fiche {{ $pet->name }}</a>
            </li>
        @endforeach
    </ul>
@endsection
