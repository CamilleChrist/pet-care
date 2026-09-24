<?php

namespace App\View\Components\Nav;

use App\Models\Pet;
use App\Models\VaccinationRecord;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Breadcrumb extends Component
{
    public function render(): View|Closure|string
    {
        return view('components.nav.breadcrumb', ['items' => $this->build()]);
    }

    /**
     * @return array<int, array{label: string, url: ?string}>
     */
    private function build(): array
    {
        $route = request()->route();

        /** @var Pet|null $pet */
        $pet = $route?->parameter('pet');

        /** @var VaccinationRecord|null $record */
        $record = $route?->parameter('vaccinationRecord');
        $pet ??= $record?->pet;

        return match ($route?->getName()) {
            'pets.index' => [
                $this->item('Animaux'),
            ],
            'pets.create' => [
                $this->item('Animaux', route('pets.index')),
                $this->item('Nouvel animal'),
            ],
            'pets.show' => [
                $this->item('Animaux', route('pets.index')),
                $this->item($pet->name),
            ],
            'pets.edit' => [
                $this->item('Animaux', route('pets.index')),
                $this->item($pet->name, route('pets.show', $pet)),
                $this->item('Modifier'),
            ],
            'pets.weight-records.create' => [
                $this->item('Animaux', route('pets.index')),
                $this->item($pet->name, route('pets.show', $pet)),
                $this->item('Nouvelle pesée'),
            ],
            'pets.vaccination-records.index' => [
                $this->item('Animaux', route('pets.index')),
                $this->item($pet->name, route('pets.show', $pet)),
                $this->item('Vaccins'),
            ],
            'pets.vaccination-records.create' => [
                $this->item('Animaux', route('pets.index')),
                $this->item($pet->name, route('pets.show', $pet)),
                $this->item('Vaccins', route('pets.vaccination-records.index', $pet)),
                $this->item('Nouveau vaccin'),
            ],
            'vaccination-records.show' => [
                $this->item('Animaux', route('pets.index')),
                $this->item($pet->name, route('pets.show', $pet)),
                $this->item('Vaccins', route('pets.vaccination-records.index', $pet)),
                $this->item($record->display_name),
            ],
            'vaccination-records.edit' => [
                $this->item('Animaux', route('pets.index')),
                $this->item($pet->name, route('pets.show', $pet)),
                $this->item('Vaccins', route('pets.vaccination-records.index', $pet)),
                $this->item('Modifier'),
            ],
            default => [],
        };
    }

    /**
     * @return array{label: string, url: ?string}
     */
    private function item(string $label, ?string $url = null): array
    {
        return [
            'label' => $label,
            'url' => $url,
        ];
    }
}
