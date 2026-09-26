<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VaccinationRecordRequest;
use App\Models\Pet;
use App\Models\VaccinationRecord;
use App\Models\Vaccine;
use Illuminate\Http\Request;

class VaccinationRecordController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $pet = $request->integer('pet') ?: null;

        return view('admin.vaccination-records.create', ['pet' => $pet] + $this->formOptions());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VaccinationRecordRequest $request)
    {
        $record = VaccinationRecord::create($request->validated());

        return redirect()->route('admin.pets.show', $record->pet_id)->with('success', 'Succès ! Vaccination enregistrée');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VaccinationRecord $vaccinationRecord)
    {
        return view('admin.vaccination-records.edit', ['record' => $vaccinationRecord] + $this->formOptions());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VaccinationRecordRequest $request, VaccinationRecord $vaccinationRecord)
    {
        $vaccinationRecord->update($request->validated());

        return redirect()->route('admin.pets.show', $vaccinationRecord->pet_id)->with('success', 'Succès ! Vaccination mise à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VaccinationRecord $vaccinationRecord)
    {
        $pet = $vaccinationRecord->pet_id;
        $vaccinationRecord->delete();

        return redirect()->route('admin.pets.show', $pet)->with('success', 'Succès ! Vaccination supprimée');
    }

    /**
     * Listes déroulantes partagées par les formulaires de création et d'édition.
     *
     * @return array<string, mixed>
     */
    private function formOptions(): array
    {
        return [
            'pets' => Pet::with('user')->orderBy('name')->get(),
            'vaccines' => Vaccine::orderBy('species')->orderBy('name')->get(),
        ];
    }
}
