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
        $pets = auth()->getUser()->pets;

        return view('pets.index', compact('pets'));
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
        $data = $request->validated();

        if ($image = $request->file('photo')) {
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

        if ($pet->photo_path) {
            Storage::disk('public')->delete($pet->photo_path);
        }

        return redirect()->route('dashboard')->with('success', $name.' a été supprimé !');
    }
}
