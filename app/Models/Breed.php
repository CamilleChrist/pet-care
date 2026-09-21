<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[WithoutTimestamps]
class Breed extends Model
{
    use HasFactory;

    public function speciesLabel(): string
    {
        return match ($this->species) {
            'dog' => 'Chien',
            'cat' => 'Chat',
        };
    }
}
