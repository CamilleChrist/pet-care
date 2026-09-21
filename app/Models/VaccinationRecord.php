<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VaccinationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'vaccine_id',
        'custom_name',
        'administered_at',
        'next_due_at',
        'veterinarian_name',
        'clinic_name',
        'lot_number',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'administered_at' => 'date',
            'next_due_at' => 'date',
        ];
    }

    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->vaccine?->name ?? $this->custom_name,
        );
    }

    protected function daysUntilDue(): Attribute
    {
        return Attribute::make(
            get: fn () => (int) today()->diffInDays($this->next_due_at, false),
        );
    }

    /** late | due (dans les 30 jours) | done */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn () => match (true) {
                $this->days_until_due < 0 => 'late',
                $this->days_until_due <= 30 => 'due',
                default => 'done',
            },
        );
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->status) {
                'late' => 'Vaccins en retard',
                'due' => 'Vaccins à faire ' . trans_choice("{0} aujourd'hui|{1} demain|[2,*] dans :count jours", $this->days_until_due),
                'done' => 'Vaccins à jour',
            },
        );
    }

    public function vaccine(): BelongsTo
    {
        return $this->belongsTo(Vaccine::class);
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }
}
