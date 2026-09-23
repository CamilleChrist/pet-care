<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVaccinationRecordRequest;
use App\Http\Requests\UpdateVaccinationRecordRequest;
use App\Models\Pet;
use App\Models\VaccinationRecord;
use App\Models\Vaccine;

class VaccinationRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Pet $pet)
    {
        $records = $pet->vaccinationRecords;
        $late = $pet->reminders()->where('status', 'late')->count();

        $vaccines = $records->sortByDesc('administered_at')
            ->groupBy('display_name')
            ->sortBy(fn ($injections) => $injections->first()->next_due_at?->timestamp ?? PHP_INT_MAX);

        $title = 'Vaccins — ' . $pet->name;
        $description = $records->isEmpty()
            ? 'Aucun vaccin enregistré'
            : collect([
                trans_choice('{1} :count enregistrement|[2,*] :count enregistrements', $records->count()),
                trans_choice('{1} :count vaccin|[2,*] :count vaccins', $records->unique('display_name')->count()),
                $late ? trans_choice('{1} :count rappel dépassé|[2,*] :count rappels dépassés', $late) : null,
            ])->filter()->implode(' · ');

        return view('vaccination-records.index', compact('pet', 'vaccines', 'title', 'description'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Pet $pet)
    {
        $vaccines = Vaccine::when(
            $pet->breed,
            fn ($query) => $query->where('species', $pet->breed->species)
        )->get();

        return view('vaccination-records.create', compact('pet', 'vaccines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVaccinationRecordRequest $request, Pet $pet)
    {
        $pet->vaccinationRecords()->create($request->validated());

        return redirect()
            ->route('pets.vaccination-records.index', $pet)
            ->with('success', 'Vaccin ajouté pour '.$pet->name);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VaccinationRecord $vaccinationRecord)
    {
        $vaccines = Vaccine::when(
            $vaccinationRecord->pet->breed,
            fn ($query) => $query->where('species', $vaccinationRecord->pet->breed->species)
        )->get();

        return view('vaccination-records.edit', compact('vaccinationRecord', 'vaccines'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVaccinationRecordRequest $request, VaccinationRecord $vaccinationRecord)
    {
        $vaccinationRecord->update($request->validated());

        return redirect()
            ->route('pets.vaccination-records.index', $vaccinationRecord->pet)
            ->with('success', 'Vaccin mis à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VaccinationRecord $vaccinationRecord)
    {
        $vaccinationRecord->delete();

        return redirect()
            ->route('pets.vaccination-records.index', $vaccinationRecord->pet_id)
            ->with('success', 'Le vaccin a été supprimé');
    }
}
