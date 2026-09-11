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
                        <form method="post" action="{{ route('weight-records.destroy', $record) }}">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="text-red-600">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a class="inline-block bg-blue-600 text-white p-2 mt-4 self-start" href="{{ route('pets.weight-records.create', $pet) }}">
        Ajouter un poids
    </a>
@endsection
