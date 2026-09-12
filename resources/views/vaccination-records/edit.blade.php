@extends('layouts.base')

@section('content')

    <h1 class="text-xl font-bold mb-4">Modifier un vaccin</h1>

    <p class="mb-4">Modifier un vaccin</p>

    @include('vaccination-records._partials.form', [
      'action' => route('vaccination-records.update', $vaccinationRecord),
      'method' => 'PATCH',
      'vaccinationRecord' => $vaccinationRecord,
      'submitLabel' => 'Modifier le vaccin',
    ])

@endsection
