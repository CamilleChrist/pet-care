@extends('layouts.base')

@section('content')
    <h1>Modifier mon animal : {{ $pet->name }}</h1>

    @include('pets._partials.form', [
      'action' => route('pets.update', $pet),
      'method' => 'PUT',
      'pet' => $pet,
      'breeds' => $breeds,
      'genders' => $genders,
      'submitLabel' => 'Enregistrer',
    ])

@endsection

@vite('resources/js/pet-form.js')
