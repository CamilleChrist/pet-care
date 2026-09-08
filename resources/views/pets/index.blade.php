@extends('layouts.base')

@section('content')

    <h1>Mes Pet</h1>

    <p>Liste des mes animaux</p>

    <ul>
        @foreach($pets as $pet)
            <li>
                <a href="{{ route('pets.show', [$pet->id]) }}">Voir la fiche {{ $pet->name }}</a>
            </li>
        @endforeach
    </ul>
@endsection
