<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::withCount('pets')->latest()->paginate(20);

        $description = trans_choice('{0} Aucun inscrit|{1} :count inscrit|[2,*] :count inscrits', $users->total());

        return view('admin.users.index', compact('users', 'description'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $pets = $user->pets()->with(['breed', 'latestWeightRecord', 'vaccinationRecords.vaccine'])->get();

        return view('admin.users.show', compact('user', 'pets'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = UserRole::cases();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        return redirect()->route('admin.users.show', $user)->with('success', 'Succès ! Utilisateur mis à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        abort_if($user->is($request->user()), 403);

        Storage::disk('public')->delete($user->pets->pluck('photo_path')->filter()->all());

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Succès ! Compte supprimé');
    }
}
