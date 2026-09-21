<?php

namespace App\View\Components\Vaccine;

use App\Models\VaccinationRecord;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Item extends Component
{
    /** late | due | done */
    public string $status;

    public function __construct(
        public VaccinationRecord $record,
        public bool $withPet = false,
    ) {
        $this->status = $record->status;
    }

    public function render(): View|Closure|string
    {
        return view('components.vaccine.item', [
            'icon' => ['late' => 'triangle-alert', 'due' => 'calendar-clock', 'done' => 'circle-check'][$this->status],
            'tone' => ['late' => 'danger', 'due' => 'warning', 'done' => 'success'][$this->status],
            'badge' => $this->record->status_label,
            'description' => $this->description(),
        ]);
    }

    private function description(): string
    {
        return ($this->withPet ? $this->record->pet->name.' · ' : '')
            .($this->status === 'late' ? 'Rappel dépassé le ' : 'Rappel le ')
            .$this->record->next_due_at->isoFormat('LL');
    }
}
