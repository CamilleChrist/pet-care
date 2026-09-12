@extends('layouts.base')

@section('content')

    <h1 class="text-xl font-bold mb-4">Ajouter un vaccin</h1>

    <p class="mb-4">Ajout d'un vaccin</p>

    @include('vaccination-records._partials.form', [
      'action' => route('pets.vaccination-records.store', $pet),
      'method' => 'POST',
      'vaccinationRecord' => null,
      'pet' => $pet,
      'submitLabel' => 'Ajouter un vaccin',
    ])

@endsection
