@extends('layouts.base')

@section('content')

    <h1>Create Pet</h1>

    <p>Ajout d'un animal</p>

    @include('pets._partials.form', [
      'action' => route('pets.store'),
      'method' => 'POST',
      'pet' => null,
      'breeds' => $breeds,
      'submitLabel' => 'Créer un pet',
    ]);

@endsection


@vite('resources/js/pet-form.js')
