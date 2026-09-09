<?php

namespace App\Models;

use App\Enums\PetGender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gender',
        'birth_date',
        'name',
        'breed_id',
        'last_vet_visit_at',
        'health_notes',
    ];

    protected function casts(): array
    {
        return ['gender' => PetGender::class];
    }

    public function breed(): BelongsTo
    {
        return $this->belongsTo(Breed::class);
    }
}
