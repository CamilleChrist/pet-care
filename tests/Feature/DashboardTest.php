<?php

use App\Models\Pet;
use App\Models\User;
use App\Models\VaccinationRecord;
use App\Models\Vaccine;
use App\Models\WeightRecord;

test('guests cannot access the dashboard', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});

test('a user without pets sees the empty state', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Aucun animal enregistré')
        ->assertDontSee('Mes animaux');
});

test('the dashboard shows each pet current weight, trend and weight curve', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id, 'name' => 'Bilou']);
    WeightRecord::factory()->create(['pet_id' => $pet->id, 'weight' => 21.4, 'recorded_at' => '2026-08-08 10:00:00']);
    WeightRecord::factory()->create(['pet_id' => $pet->id, 'weight' => 21.8, 'recorded_at' => '2026-09-08 10:00:00']);

    $this->actingAs($user)->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Bilou')
        ->assertSee('21,8')
        ->assertSee('+0,4 kg')
        ->assertSee('Mesuré le 8 septembre 2026')
        ->assertSee('class="weight-chart__line"', false);
});

test('the dashboard lists the latest booster of each vaccine and flags overdue ones', function () {
    $this->travelTo('2026-09-20');

    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id, 'name' => 'Bilou']);
    $lepto = Vaccine::factory()->create(['name' => 'Leptospirose']);
    $rage = Vaccine::factory()->create(['name' => 'Rage']);

    // Ancienne injection dépassée mais remplacée par une plus récente : ne doit pas apparaître en retard.
    VaccinationRecord::factory()->create(['pet_id' => $pet->id, 'vaccine_id' => $rage->id, 'administered_at' => '2024-01-10', 'next_due_at' => '2025-01-10']);
    VaccinationRecord::factory()->create(['pet_id' => $pet->id, 'vaccine_id' => $rage->id, 'administered_at' => '2026-01-10', 'next_due_at' => '2028-01-10']);
    VaccinationRecord::factory()->create(['pet_id' => $pet->id, 'vaccine_id' => $lepto->id, 'administered_at' => '2025-08-02', 'next_due_at' => '2026-08-02']);

    $this->actingAs($user)->get(route('dashboard'))
        ->assertOk()
        ->assertSeeInOrder(['Rappel dépassé', 'Leptospirose - Bilou,', 'échéance du 2 août 2026'])
        ->assertSee('Bilou · Rappel dépassé le 2 août 2026')
        ->assertSee('Bilou · Rappel le 10 janvier 2028')
        ->assertDontSee('10 janvier 2025');
});

test('the dashboard never shows another user pets', function () {
    $user = User::factory()->create();
    Pet::factory()->create(['name' => 'Intrus']);

    $this->actingAs($user)->get(route('dashboard'))
        ->assertOk()
        ->assertDontSee('Intrus');
});
