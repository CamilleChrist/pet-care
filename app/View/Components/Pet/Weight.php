<?php

namespace App\View\Components\Pet;

use App\Models\Pet;
use App\Models\WeightRecord;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Weight extends Component
{
    public ?WeightRecord $latest;

    /** Écart avec la pesée précédente, arrondi au dixième ; null s'il n'y en a pas. */
    public ?float $delta;

    /** up | down | flat | null */
    public ?string $trend;

    public function __construct(public Pet $pet)
    {
        $this->latest = $pet->weightRecords->first();
        $previous = $pet->weightRecords->get(1);

        $this->delta = $previous ? round($this->latest->weight - $previous->weight, 1) : null;

        $this->trend = match (true) {
            $this->delta === null => null,
            $this->delta > 0 => 'up',
            $this->delta < 0 => 'down',
            default => 'flat',
        };
    }

    public function render(): View|Closure|string
    {
        return view('components.pet.weight', [
            'value' => $this->latest ? number_format($this->latest->weight, 1, ',', ' ') : null,
            'trendLabel' => $this->trendLabel(),
            'records' => $this->pet->weightRecords->take(12)->reverse(),
        ]);
    }

    private function trendLabel(): ?string
    {
        return match ($this->trend) {
            null => null,
            'flat' => '±0,0 kg',
            default => str_replace('.', ',', sprintf('%+.1f', $this->delta)).' kg',
        };
    }
}
