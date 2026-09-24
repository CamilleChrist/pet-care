<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;

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
}
