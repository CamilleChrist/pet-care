<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWeightRecordRequest;
use App\Models\Pet;
use App\Models\WeightRecord;

class WeightRecordController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Pet $pet)
    {
        return view('weight-records.create', [
            'pet' => $pet,
            'recorded' => $pet->weightRecords()->take(3)->get(),
            'title' => 'Ajouter un poids',
            'description' => collect([$pet->name, $pet->breed?->name])->filter()->implode(' · '),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWeightRecordRequest $request, Pet $pet)
    {
        $pet->weightRecords()->create($request->validated());

        return redirect()
            ->route('pets.show', $pet)
            ->with('success', 'Poids ajouté pour '.$pet->name);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WeightRecord $weightRecord)
    {
        $weightRecord->delete();

        return redirect()
            ->route('pets.show', $weightRecord->pet_id)
            ->with('success', 'Un poids a bien été supprimé');
    }
}
