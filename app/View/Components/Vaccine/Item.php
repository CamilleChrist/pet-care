<?php

namespace App\View\Components\Vaccine;

use App\Models\VaccinationRecord;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Item extends Component
{
    /** Jours restants avant l'échéance (négatif si dépassée). */
    public int $days;

    /** late | due | done */
    public string $status;

    public function __construct(
        public VaccinationRecord $record,
        public bool $withPet = false,
    ) {
        $this->days = (int) today()->diffInDays($record->next_due_at, false);

        $this->status = match (true) {
            $this->days < 0 => 'late',
            $this->days <= 30 => 'due',
            default => 'done',
        };
    }

    public function render(): View|Closure|string
    {
        return view('components.vaccine.item', [
            'icon' => ['late' => 'triangle-alert', 'due' => 'calendar-clock', 'done' => 'circle-check'][$this->status],
            'tone' => ['late' => 'danger', 'due' => 'warning', 'done' => 'success'][$this->status],
            'badge' => $this->badge(),
            'description' => $this->description(),
        ]);
    }

    private function badge(): string
    {
        return match ($this->status) {
            'late' => 'En retard',
            'due' => trans_choice("{0} Aujourd'hui|{1} Demain|[2,*] Dans :count jours", $this->days),
            'done' => 'À jour',
        };
    }

    private function description(): string
    {
        return ($this->withPet ? $this->record->pet->name.' · ' : '')
            .($this->status === 'late' ? 'Rappel dépassé le ' : 'Rappel le ')
            .$this->record->next_due_at->isoFormat('LL');
    }
}
