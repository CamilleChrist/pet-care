<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VaccineRequest;
use App\Models\Vaccine;

class VaccineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vaccines = Vaccine::orderBy('species')->orderBy('name')->paginate(30);

        $description = trans_choice('{0} Aucun vaccin|{1} :count vaccin|[2,*] :count vaccins', $vaccines->total());

        return view('admin.vaccines.index', compact('vaccines', 'description'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.vaccines.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VaccineRequest $request)
    {
        Vaccine::create($request->validated());

        return redirect()->route('admin.vaccines.index')->with('success', 'Succès ! Vaccin créé');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vaccine $vaccine)
    {
        return view('admin.vaccines.edit', compact('vaccine'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VaccineRequest $request, Vaccine $vaccine)
    {
        $vaccine->update($request->validated());

        return redirect()->route('admin.vaccines.index')->with('success', 'Succès ! Vaccin mis à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vaccine $vaccine)
    {
        $vaccine->delete();

        return redirect()->route('admin.vaccines.index')->with('success', 'Succès ! Vaccin supprimé');
    }
}
