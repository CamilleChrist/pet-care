<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[WithoutTimestamps]
class Breed extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'species',
    ];

    public function pets(): HasMany
    {
        return $this->hasMany(Pet::class);
    }

    public function speciesLabel(): string
    {
        return match ($this->species) {
            'dog' => 'Chien',
            'cat' => 'Chat',
        };
    }
}
