<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVaccinationRecordRequest;
use App\Http\Requests\UpdateVaccinationRecordRequest;
use App\Models\Pet;
use App\Models\VaccinationRecord;
use App\Models\Vaccine;
use Illuminate\Support\Collection;

class VaccinationRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Pet $pet)
    {
        $records = $pet->vaccinationRecords;
        $late = $pet->reminders()->where('status', 'late')->count();

        $vaccines = $this->groupByVaccine($records);

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
        $title = 'Ajouter un vaccin';
        $description = collect([$pet->name, $pet->breed?->name])->filter()->implode(' · ');

        return view('vaccination-records.create', [
            'pet' => $pet,
            'vaccines' => Vaccine::forPet($pet)->get(),
            'recorded' => $this->groupByVaccine($pet->vaccinationRecords),
            'title' => $title,
            'description' => $description,
        ]);
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
     * Display the specified resource.
     */
    public function show(VaccinationRecord $vaccinationRecord)
    {
        $pet = $vaccinationRecord->pet;

        // toutes ses injections du même vaccin, la plus récente d'abord.
        $injections = $pet->vaccinationRecords
            ->where('display_name', $vaccinationRecord->display_name)
            ->sortByDesc('administered_at')
            ->values();

        $title = $vaccinationRecord->display_name;
        $description = $pet->name.' · '.trans_choice('{1} :count injection|[2,*] :count injections', $injections->count());

        return view('vaccination-records.show', compact('pet', 'injections', 'title', 'description'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VaccinationRecord $vaccinationRecord)
    {
        $pet = $vaccinationRecord->pet;

        $title = 'Modifier un vaccin';
        $description = collect([$vaccinationRecord->display_name, $pet->name])->filter()->implode(' · ');

        return view('vaccination-records.edit', [
            'vaccinationRecord' => $vaccinationRecord,
            'pet' => $pet,
            'vaccines' => Vaccine::forPet($pet)->get(),
            // Le vaccin en cours de modification est déjà sous les yeux : on ne le répète pas.
            'recorded' => $this->groupByVaccine($pet->vaccinationRecords)->forget($vaccinationRecord->display_name),
            'title' => $title,
            'description' => $description,
        ]);
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

    /** Un groupe par vaccin (injection la plus récente en tête), les échéances les plus proches d'abord. */
    private function groupByVaccine(Collection $records): Collection
    {
        return $records->sortByDesc('administered_at')
            ->groupBy('display_name')
            ->sortBy(fn ($injections) => $injections->first()->next_due_at?->timestamp ?? PHP_INT_MAX);
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
