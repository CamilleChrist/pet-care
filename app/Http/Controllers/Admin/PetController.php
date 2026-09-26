<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PetGender;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePetRequest;
use App\Http\Requests\Admin\UpdatePetRequest;
use App\Models\Breed;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $owner = $request->integer('user') ?: null;

        $pets = Pet::with(['user', 'breed'])
            ->when($owner, fn ($query) => $query->where('user_id', $owner))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $description = trans_choice('{0} Aucun animal|{1} :count animal|[2,*] :count animaux', $pets->total());

        return view('admin.pets.index', compact('pets', 'description', 'owner'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pets.create', $this->formOptions());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePetRequest $request)
    {
        $pet = Pet::create($request->validated());

        return redirect()->route('admin.pets.index')->with('success', 'Succès ! '.$pet->name.' a été créé');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pet $pet)
    {
        $pet->load(['user', 'breed']);

        $vaccinationRecords = $pet->vaccinationRecords()->with('vaccine')->latest('administered_at')->get();
        $weightRecords = $pet->weightRecords;

        return view('admin.pets.show', compact('pet', 'vaccinationRecords', 'weightRecords'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pet $pet)
    {
        return view('admin.pets.edit', ['pet' => $pet] + $this->formOptions());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePetRequest $request, Pet $pet)
    {
        $pet->update($request->safe()->except('remove_photo'));

        if ($request->boolean('remove_photo')) {
            $this->deletePhoto($pet);
            $pet->update(['photo_path' => null]);
        }

        return redirect()->route('admin.pets.index')->with('success', 'Succès ! '.$pet->name.' a été mis à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pet $pet)
    {
        $name = $pet->name;
        $pet->delete();
        $this->deletePhoto($pet);

        return redirect()->route('admin.pets.index')->with('success', 'Succès ! '.$name.' a été supprimé');
    }

    /**
     * Listes déroulantes partagées par les formulaires de création et d'édition.
     *
     * @return array<string, mixed>
     */
    private function formOptions(): array
    {
        return [
            'users' => User::orderBy('name')->get(),
            'breeds' => Breed::orderBy('name')->get(),
            'genders' => PetGender::cases(),
        ];
    }

    private function deletePhoto(Pet $pet): void
    {
        if ($pet->photo_path) {
            Storage::disk('public')->delete($pet->photo_path);
        }
    }
}
