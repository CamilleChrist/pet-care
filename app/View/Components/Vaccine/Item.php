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
        public bool $link = true, // false : ligne non cliquable, le slot reçoit les actions (page vaccins)
        public int $injections = 1,
    ) {
        $this->status = $record->status;
    }

    public function render(): View|Closure|string
    {
        return view('components.vaccine.item', [
            'icon' => ['late' => 'triangle-alert', 'due' => 'calendar-clock', 'done' => 'circle-check'][$this->status],
            'tone' => $this->record->status_tone,
            'badge' => $this->record->status_label,
            'description' => $this->description(),
        ]);
    }

    private function description(): string
    {
        $parts = [];

        if ($this->withPet) {
            $parts[] = $this->record->pet->name;
        }

        if ($this->injections > 1) {
            $parts[] = "{$this->injections} injections";
        }

        if ($this->record->next_due_at) {
            $label = $this->status === 'late'
                ? 'Rappel dépassé le '
                : 'Rappel le ';

            $parts[] = $label . $this->record->next_due_at->isoFormat('LL');
        } else {
            $parts[] = 'Fait le ' . $this->record->administered_at->isoFormat('LL');
        }

        return implode(' · ', $parts);
    }
}
