@extends('layouts.base')

@section('content')
    <h1 class="text-xl font-bold mb-4">Détail d'un pet</h1>

    <ul class="flex flex-col gap-1 mb-4">
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

    <a class="inline-block mb-4 text-blue-600 hover:underline" href="{{ route('pets.edit', [$pet->id]) }}">Modifier</a>

    <form method="post" action="{{ route('pets.destroy', [$pet->id]) }}">
        @csrf
        @method('DELETE')

        <button type="submit" class="text-red-600">Supprimer</button>
    </form>
@endsection
