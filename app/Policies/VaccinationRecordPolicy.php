<?php

namespace App\Policies;

use App\Models\Pet;
use App\Models\User;
use App\Models\VaccinationRecord;
use Illuminate\Auth\Access\Response;

class VaccinationRecordPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Pet $pet): Response
    {
        return $user->id === $pet->user_id
            ? Response::allow()
            : Response::deny('Vous ne possedez pas cet animal.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Pet $pet): Response
    {
        return $user->id === $pet->user_id
            ? Response::allow()
            : Response::deny('Vous ne possedez pas cet animal.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, VaccinationRecord $vaccinationRecord): Response
    {
        return $user->id === $vaccinationRecord->pet->user_id
            ? Response::allow()
            : Response::deny('Vous ne possedez pas cet animal.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, VaccinationRecord $vaccinationRecord): Response
    {
        return $user->id === $vaccinationRecord->pet->user_id
            ? Response::allow()
            : Response::deny('Vous ne possedez pas cet animal.');
    }
}
