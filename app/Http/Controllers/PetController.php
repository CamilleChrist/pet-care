<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Models\Breed;
use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pets = Pet::all();

        return view('pets.index', compact('pets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breeds = Breed::all();

        return view('pets.create', compact('breeds'));
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
    public function edit(Pet $pet, Breed $breed)
    {
        $breeds = Breed::all();

        return view('pets.edit', compact('pet', 'breeds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePetRequest $request, Pet $pet)
    {
        $pet->update($request->validated());

        return redirect()->route('pets.show', [$pet->id])->with('success', $pet->name . ' a été mis à jour !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pet $pet)
    {
        $name = $pet->name;
        $pet->delete();

        return redirect()->route('dashboard')->with('success', $name . ' a été supprimé !')    ;
    }
}
