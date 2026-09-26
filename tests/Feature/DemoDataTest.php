<?php

use App\Models\Breed;
use App\Models\User;
use App\Models\Vaccine;

it('seeds the reference tables', function () {
    $this->seed();

    expect(Breed::count())->toBeGreaterThan(0)
        ->and(Vaccine::count())->toBeGreaterThan(0);
});

it('seeds a demo account with pets, weighings and reminders', function () {
    $this->seed();

    $user = User::firstWhere('email', 'test@example.com');
    $pet = $user->pets()->firstWhere('name', 'Nala');

    expect($user->pets)->toHaveCount(2)
        ->and($pet->breed->name)->toBe('Labrador Retriever')
        ->and($pet->weightRecords)->toHaveCount(6)
        ->and($pet->reminders()->pluck('status')->all())->toContain('late', 'due');
});
