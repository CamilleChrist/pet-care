<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeightRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'weight',
        'recorded_at',
    ];

    protected function casts(): array
    {
        return ['recorded_at' => 'datetime'];
    }

    /** Poids au format français, sans l'unité : « 21,8 ». */
    protected function formattedWeight(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->weight, 1, ',', ' '),
        );
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }
}
