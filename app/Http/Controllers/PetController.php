<?php

namespace App\Http\Controllers;

use App\Enums\PetGender;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Models\Breed;
use App\Models\Pet;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pets = auth()->user()->pets()->with(['breed', 'latestWeightRecord', 'vaccinationRecords.vaccine'])->get();

        $description = trans_choice('{0} Aucun animal enregistré|{1} :count animal|[2,*] :count animaux', $pets->count()).' · poids et vaccins suivis';

        return view('pets.index', compact('pets', 'description'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breeds = Breed::all();
        $genders = PetGender::cases();

        return view('pets.create', compact('breeds', 'genders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePetRequest $request)
    {
        Pet::create($request->validated());

        return redirect()->route('dashboard')->with('success', 'Succès ! Animal créé');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pet $pet)
    {
        return view('pets.show', compact('pet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pet $pet)
    {
        $breeds = Breed::all();
        $genders = PetGender::cases();

        return view('pets.edit', compact('pet', 'breeds', 'genders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePetRequest $request, Pet $pet)
    {
        $data = $request->safe()->except('remove_photo');

        if ($request->boolean('remove_photo')) {
            $this->deletePhoto($pet);
            $data['photo_path'] = null;
        }

        if ($image = $request->file('photo')) {
            $this->deletePhoto($pet);

            $year = now()->year;
            $month = now()->month;
            $filename = $pet->id.'_'.Str::slug($pet->name).'.'.$image->extension();

            $data['photo_path'] = $image->storeAs("pets/{$year}/{$month}", $filename, 'public');
        }

        $pet->update($data);

        return redirect()->route('pets.show', [$pet->id])->with('success', $pet->name.' a été mis à jour !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pet $pet)
    {
        $name = $pet->name;
        $pet->delete();
        $this->deletePhoto($pet);

        return redirect()->route('dashboard')->with('success', $name.' a été supprimé !');
    }

    private function deletePhoto(Pet $pet): void
    {
        if ($pet->photo_path) {
            Storage::disk('public')->delete($pet->photo_path);
        }
    }
}
