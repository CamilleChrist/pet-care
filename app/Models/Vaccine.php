<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/***
 * @method static Builder<static> forPet(Pet $pet)
 */
#[WithoutTimestamps]
class Vaccine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'species',
        'description',
    ];

    /** Get only the vaccines for the pet breed */
    #[Scope]
    protected function forPet(Builder $query, Pet $pet): void
    {
        $query->when(
            $pet->breed,
            fn ($query) => $query->where('species', $pet->breed->species)
        );
    }
}
