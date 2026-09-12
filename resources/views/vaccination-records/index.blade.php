@extends('layouts.base')

@section('content')

    <h1 class="text-xl font-bold mb-4">Suivi des vaccins</h1>

    <p class="mb-4">Suivi des vaccins de {{ $pet->name }}</p>

    @if($pet->vaccinationRecords->isNotEmpty())
        <table class="w-full text-left">
            <thead>
            <tr>
                <th>Vaccin effectué</th>
                <th>Date</th>
                <th>Prochain vaccin</th>
                <th>Nom du vétérinaire</th>
                <th>Nom de la clinique</th>
                <th>Numéro du lot</th>
                <th>Notes</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                        @foreach($pet->vaccinationRecords as $record)
                            <tr>
                                <td>{{ $record->displayName }}</td>
                                <td>{{ $record->administered_at->format('j F Y') }}</td>
                                <td>@if ($record->next_due_at) {{ $record->next_due_at->format('j F Y') }}@endif</td>
                                <td>@if ($record->veterinarian_name) {{ $record->veterinarian_name }}@endif</td>
                                <td>@if ($record->clinic_name) {{ $record->clinic_name }}@endif</td>
                                <td>@if ($record->lot_number) {{ $record->lot_number }}@endif</td>
                                <td>@if ($record->notes) {{ $record->notes }}@endif</td>

                                <td>
                                    <a href="{{ route('vaccination-records.edit', $record) }}">Modifier</a>
                                </td>

                                <td>
                                    <form method="post" action="{{ route('vaccination-records.destroy', $record) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="text-red-600">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
            </tbody>
        </table>
    @else
        <p>Aucun vaccin enregistré pour le moment pour {{ $pet->name }}</p>
    @endif

    <a class="inline-block bg-blue-600 text-white p-2 mt-4 self-start"
       href="{{ route('pets.vaccination-records.create', $pet) }}">
        Ajouter un vaccin
    </a>
@endsection
