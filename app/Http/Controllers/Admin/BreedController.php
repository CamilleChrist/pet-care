<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BreedRequest;
use App\Models\Breed;

class BreedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breeds = Breed::withCount('pets')->orderBy('species')->orderBy('name')->paginate(30);

        $description = trans_choice('{0} Aucune race|{1} :count race|[2,*] :count races', $breeds->total());

        return view('admin.breeds.index', compact('breeds', 'description'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.breeds.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BreedRequest $request)
    {
        Breed::create($request->validated());

        return redirect()->route('admin.breeds.index')->with('success', 'Succès ! Race créée');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Breed $breed)
    {
        return view('admin.breeds.edit', compact('breed'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BreedRequest $request, Breed $breed)
    {
        $breed->update($request->validated());

        return redirect()->route('admin.breeds.index')->with('success', 'Succès ! Race mise à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Breed $breed)
    {
        $breed->delete();

        return redirect()->route('admin.breeds.index')->with('success', 'Succès ! Race supprimée');
    }
}
