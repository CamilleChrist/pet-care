<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWeightRecordRequest;
use App\Models\Pet;
use App\Models\WeightRecord;

class WeightRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Pet $pet)
    {
        return view('weight-records.index', compact('pet'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Pet $pet)
    {
        return view('weight-records.create', compact('pet'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWeightRecordRequest $request, Pet $pet)
    {
        $pet->weightRecords()->create($request->validated());

        return redirect()
            ->route('pets.weight-records.index', $pet)
            ->with('success', 'Poids ajouté pour '.$pet->name);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WeightRecord $weightRecord)
    {
        $weightRecord->delete();

        return redirect()
            ->route('pets.weight-records.index', $weightRecord->pet_id)
            ->with('success', 'Un record a été supprimé');
    }
}
