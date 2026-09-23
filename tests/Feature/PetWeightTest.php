<?php

use App\Models\Pet;
use App\Models\WeightRecord;
use App\View\Components\Pet\Weight;

test('the weight trend compares the two latest weighings', function (array $weights, ?string $trend, ?float $delta) {
    $pet = Pet::factory()->create();

    foreach ($weights as $i => $weight) {
        WeightRecord::factory()->create(['pet_id' => $pet->id, 'weight' => $weight, 'recorded_at' => "2026-0{$i}-01 10:00:00"]);
    }

    $component = new Weight($pet->fresh());

    expect($component->trend)->toBe($trend)
        ->and($component->delta)->toBe($delta);
})->with([
    'gaining' => [[1 => 21.4, 2 => 21.8], 'up', 0.4],
    'losing' => [[1 => 4.6, 2 => 4.4], 'down', -0.2],
    'stable' => [[1 => 4.6, 2 => 4.6], 'flat', 0.0],
    'single weighing' => [[1 => 4.6], null, null],
]);
