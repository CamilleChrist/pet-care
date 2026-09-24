<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the account form.
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * Update the name and the e-mail of the authenticated user.
     */
    public function update(UpdateProfileRequest $request)
    {
        $request->user()->update($request->validated());

        return redirect()->route('profile.edit')->with('success', 'Votre profil a été mis à jour !');
    }

    /**
     * Show the password form.
     */
    public function editPassword()
    {
        return view('profile.password');
    }

    /**
     * Replace the password of the authenticated user.
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $request->user()->update($request->safe()->only('password'));

        return redirect()->route('profile.edit')->with('success', 'Votre mot de passe a été modifié !');
    }

    /**
     * Delete the account, its pets and everything that hangs off them.
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        // Les animaux (et leurs pesées/vaccins) partent en cascade ; les fichiers sur le disque, non.
        Storage::disk('public')->delete($user->pets->pluck('photo_path')->filter()->all());

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Votre compte a été supprimé.');
    }
}
