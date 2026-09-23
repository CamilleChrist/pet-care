<?php

use App\Models\VaccinationRecord;
use App\View\Components\Vaccine\Item;

test('a vaccine item is late, due or done depending on the days left before the booster', function (string $nextDueAt, string $status) {
    $this->travelTo('2026-09-20');

    $record = new VaccinationRecord(['next_due_at' => $nextDueAt]);

    expect((new Item($record))->status)->toBe($status);
})->with([
    'yesterday' => ['2026-09-19', 'late'],
    'today' => ['2026-09-20', 'due'],
    'in 30 days' => ['2026-10-20', 'due'],
    'in 31 days' => ['2026-10-21', 'done'],
]);
