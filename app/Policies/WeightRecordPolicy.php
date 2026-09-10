<?php

namespace App\Policies;

use App\Models\Pet;
use App\Models\User;
use App\Models\WeightRecord;
use Illuminate\Auth\Access\Response;

class WeightRecordPolicy
{
    /**
     * Determine whether the user can view the pet's weight records.
     */
    public function viewAny(User $user, Pet $pet): Response
    {
        return $user->id === $pet->user_id
            ? Response::allow()
            : Response::deny('Vous ne possedez pas cet animal.');
    }

    /**
     * Determine whether the user can add a weight record to the pet.
     */
    public function create(User $user, Pet $pet): Response
    {
        return $user->id === $pet->user_id
            ? Response::allow()
            : Response::deny('Vous ne possedez pas cet animal.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WeightRecord $weightRecord): Response
    {
        return $user->id === $weightRecord->pet->user_id
            ? Response::allow()
            : Response::deny('Vous ne possedez pas cet animal.');
    }
}
