@extends('layouts.base')

@section('content')
    <h1>Détail d'un pet</h1>

    <ul>
        <li>Nom : {{ $pet->name }}</li>
        <li>Genre : {{ $pet->gender->label() }}</li>
        <li>Race : {{ $pet->breed->name }}</li>
        <li>Date de naissance : {{ $pet->birth_date }}</li>
        @if( $pet->health_notes )
            <li>Notes : {{ $pet->health_notes }}</li>
        @endif

        @if( $pet->last_vet_visit_at )
            <li>Dernière visite véto : {{ $pet->last_vet_visit_at }}</li>
        @endif
    </ul>

    <a href="{{ route('pets.edit', [$pet->id]) }}">Modifier</a>

    <form method="post" action="{{ route('pets.destroy', [$pet->id]) }}">
        @csrf
        <button type="submit">Supprimer</button>
    </form>
@endsection
