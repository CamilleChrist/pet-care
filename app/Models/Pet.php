<?php

namespace App\Models;

use App\Enums\PetGender;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
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

    /** Âge lisible (« 3 ans », « 7 mois »). */
    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->birth_date)->diffForHumans(syntax: CarbonInterface::DIFF_ABSOLUTE),
        );
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

    public function latestWeightRecord(): HasOne
    {
        return $this->hasOne(WeightRecord::class)->latestOfMany('recorded_at');
    }

    public function vaccinationRecords(): HasMany
    {
        return $this->hasMany(VaccinationRecord::class);
    }
}
