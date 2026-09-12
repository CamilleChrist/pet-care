<?php

namespace App\Models;

use App\Enums\PetGender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gender',
        'birth_date',
        'name',
        'breed_id',
        'photo_path',
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

    public function photoUrl(): string
    {
        return Storage::url($this->photo_path);
    }

    public function weightRecords(): HasMany
    {
        return $this->hasMany(WeightRecord::class)->orderBy('recorded_at', 'desc');
    }

    public function vaccinationRecords(): HasMany
    {
        return $this->hasMany(VaccinationRecord::class);
    }
}
