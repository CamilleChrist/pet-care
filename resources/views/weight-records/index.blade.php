@extends('layouts.base')

@section('content')

    <h1 class="text-xl font-bold mb-4">Suivi du poids</h1>

    <p class="mb-4">Suivi du poids de {{ $pet->name }}</p>

    <table class="w-full text-left">
        <thead>
            <tr>
                <th>Poids</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($pet->weightRecords as $record)
                <tr>
                    <td>{{ $record->weight }}kg</td>
                    <td>{{ $record->recorded_at->format('j F Y') }}</td>
                    <td>
                        <button type="button" class="text-red-600"
                                data-dialog-open="delete-weight-record-{{ $record->id }}">
                            Supprimer
                        </button>

                        <x-ui.confirm-delete id="delete-weight-record-{{ $record->id }}"
                                             :action="route('weight-records.destroy', $record)"
                                             title="Supprimer cette pesée ?"
                                             description="La pesée du {{ $record->recorded_at->format('j F Y') }} sera définitivement supprimée." />
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a class="inline-block bg-blue-600 text-white p-2 mt-4 self-start" href="{{ route('pets.weight-records.create', $pet) }}">
        Ajouter un poids
    </a>
@endsection
