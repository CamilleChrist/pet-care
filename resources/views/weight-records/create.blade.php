@extends('layouts.base')

@section('content')

    <h1 class="text-xl font-bold mb-4">Ajouter un poids</h1>

    <p class="mb-4">Ajout d'un poids</p>

    @include('weight-records._partials.form', [
      'action' => route('weightrecords.store'),
      'method' => 'POST',
      'weightRecord' => null,
      'pet' => $pet,
      'submitLabel' => 'Ajouter un poids',
    ])

@endsection
