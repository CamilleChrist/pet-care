<?php

use App\Models\Pet;
use App\Models\User;
use App\Models\WeightRecord;

test('guests cannot access weight records', function () {
    $pet = Pet::factory()->create();
    $weightRecord = WeightRecord::factory()->for($pet)->create();

    $this->get(route('pets.weight-records.index', $pet))->assertRedirect(route('login'));
    $this->get(route('pets.weight-records.create', $pet))->assertRedirect(route('login'));
    $this->post(route('pets.weight-records.store', $pet))->assertRedirect(route('login'));
    $this->delete(route('weight-records.destroy', $weightRecord))->assertRedirect(route('login'));
});

test('a user sees their pet weight records in the list', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);
    WeightRecord::factory()->for($pet)->create(['weight' => 12.5]);

    $this->actingAs($user)
        ->get(route('pets.weight-records.index', $pet))
        ->assertOk()
        ->assertSee('12.5');
});

test('a user can open the form to add a weight record', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('pets.weight-records.create', $pet))
        ->assertOk();
});

test('a user can add a weight record to their pet', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->post(route('pets.weight-records.store', $pet), [
        'weight' => 12.5,
        'recorded_at' => '2026-01-15T10:00',
    ]);

    $response->assertRedirect(route('pets.weight-records.index', $pet));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('weight_records', [
        'pet_id' => $pet->id,
        'weight' => 12.5,
        'recorded_at' => '2026-01-15 10:00:00',
    ]);
});

test('adding a weight record fails with an empty payload', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->post(route('pets.weight-records.store', $pet), []);

    $response->assertSessionHasErrors(['weight', 'recorded_at']);
    $this->assertDatabaseEmpty('weight_records');
});

test('adding a weight record fails when the weight is out of range', function (float $weight) {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->post(route('pets.weight-records.store', $pet), [
        'weight' => $weight,
        'recorded_at' => '2026-01-15T10:00',
    ]);

    $response->assertSessionHasErrors([
        'weight' => 'Le champ poids doit être compris entre 0 et 150.',
    ]);
    $this->assertDatabaseEmpty('weight_records');
})->with([
    'negative' => -1,
    'above maximum' => 151,
]);

test('adding a weight record fails when the date is in the future', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->post(route('pets.weight-records.store', $pet), [
        'weight' => 12.5,
        'recorded_at' => now()->addDay()->format('Y-m-d\TH:i'),
    ]);

    $response->assertSessionHasErrors([
        'recorded_at' => 'Le champ date doit être une date antérieure ou égale à now.',
    ]);
    $this->assertDatabaseEmpty('weight_records');
});

test('adding a weight record fails when the date has the wrong format', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->post(route('pets.weight-records.store', $pet), [
        'weight' => 12.5,
        'recorded_at' => '2026-01-15 10:00:00',
    ]);

    $response->assertSessionHasErrors([
        'recorded_at' => 'Le champ date doit correspondre au format Y-m-d\TH:i.',
    ]);
    $this->assertDatabaseEmpty('weight_records');
});

test('a user cannot add a weight record to another user pet', function () {
    $pet = Pet::factory()->create();

    $response = $this->actingAs(User::factory()->create())->post(route('pets.weight-records.store', $pet), [
        'weight' => 12.5,
        'recorded_at' => '2026-01-15T10:00',
    ]);

    $response->assertForbidden();
    $this->assertDatabaseEmpty('weight_records');
});

test('a user cannot see another user pet weight records', function () {
    $pet = Pet::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('pets.weight-records.index', $pet))
        ->assertForbidden();
});

test('a user cannot open the form to add a weight record to another user pet', function () {
    $pet = Pet::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('pets.weight-records.create', $pet))
        ->assertForbidden();
});

test('a user can delete a weight record from their pet', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);
    $weightRecord = WeightRecord::factory()->for($pet)->create();

    $response = $this->actingAs($user)->delete(route('weight-records.destroy', $weightRecord));

    $response->assertRedirect(route('pets.show', $pet));
    $response->assertSessionHas('success');
    $this->assertDatabaseMissing('weight_records', ['id' => $weightRecord->id]);
});

test('a user cannot delete a weight record from another user pet', function () {
    $pet = Pet::factory()->create();
    $weightRecord = WeightRecord::factory()->for($pet)->create();

    $response = $this->actingAs(User::factory()->create())
        ->delete(route('weight-records.destroy', $weightRecord));

    $response->assertForbidden();
    $this->assertDatabaseHas('weight_records', ['id' => $weightRecord->id]);
});
