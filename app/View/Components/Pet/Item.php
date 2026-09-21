<?php

namespace App\View\Components\Pet;

use App\Models\Pet;
use App\Models\VaccinationRecord;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Item extends Component
{
    /** Prochain rappel vaccinal (dernière injection de chaque vaccin, échéance la plus proche) ; null sans rappel. */
    public ?VaccinationRecord $reminder;

    public function __construct(public Pet $pet)
    {
        $this->reminder = $pet->vaccinationRecords
            ->sortByDesc('administered_at')
            ->unique(fn (VaccinationRecord $record) => $record->display_name)
            ->filter(fn (VaccinationRecord $record) => $record->next_due_at)
            ->sortBy('next_due_at')
            ->first();
    }

    public function render(): View|Closure|string
    {
        return view('components.pet.item', [
            'tone' => $this->reminder ? ['late' => 'danger', 'due' => 'warning', 'done' => 'success'][$this->reminder->status] : null,
        ]);
    }
}
