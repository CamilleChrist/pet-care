<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WeightRecordRequest;
use App\Models\Pet;
use App\Models\WeightRecord;
use Illuminate\Http\Request;

class WeightRecordController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $pet = $request->integer('pet') ?: null;

        return view('admin.weight-records.create', ['pet' => $pet] + $this->formOptions());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WeightRecordRequest $request)
    {
        $record = WeightRecord::create($request->validated());

        return redirect()->route('admin.pets.show', $record->pet_id)->with('success', 'Succès ! Pesée enregistrée');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WeightRecord $weightRecord)
    {
        return view('admin.weight-records.edit', ['record' => $weightRecord] + $this->formOptions());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WeightRecordRequest $request, WeightRecord $weightRecord)
    {
        $weightRecord->update($request->validated());

        return redirect()->route('admin.pets.show', $weightRecord->pet_id)->with('success', 'Succès ! Pesée mise à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WeightRecord $weightRecord)
    {
        $pet = $weightRecord->pet_id;
        $weightRecord->delete();

        return redirect()->route('admin.pets.show', $pet)->with('success', 'Succès ! Pesée supprimée');
    }

    /**
     * Listes déroulantes partagées par les formulaires de création et d'édition.
     *
     * @return array<string, mixed>
     */
    private function formOptions(): array
    {
        return ['pets' => Pet::with('user')->orderBy('name')->get()];
    }
}
