<?php

use App\Models\Breed;
use App\Models\Pet;
use App\Models\User;
use App\Models\VaccinationRecord;
use App\Models\Vaccine;

test('guests cannot access vaccination records', function () {
    $pet = Pet::factory()->create();
    $vaccinationRecord = VaccinationRecord::factory()->for($pet)->create();

    $this->get(route('pets.vaccination-records.index', $pet))->assertRedirect(route('login'));
    $this->get(route('pets.vaccination-records.create', $pet))->assertRedirect(route('login'));
    $this->post(route('pets.vaccination-records.store', $pet))->assertRedirect(route('login'));
    $this->get(route('vaccination-records.edit', $vaccinationRecord))->assertRedirect(route('login'));
    $this->patch(route('vaccination-records.update', $vaccinationRecord))->assertRedirect(route('login'));
    $this->delete(route('vaccination-records.destroy', $vaccinationRecord))->assertRedirect(route('login'));
});

test('a user sees their pet vaccination records in the list', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);
    $vaccine = Vaccine::factory()->create(['name' => 'Rage']);
    VaccinationRecord::factory()->for($pet)->create(['vaccine_id' => $vaccine->id]);

    $this->actingAs($user)
        ->get(route('pets.vaccination-records.index', $pet))
        ->assertOk()
        ->assertSee('Rage');
});

test('a user can open the form to add a vaccination record', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('pets.vaccination-records.create', $pet))
        ->assertOk();
});

test('a user can add a vaccination record with a catalog vaccine to their pet', function () {
    $user = User::factory()->create();
    $breed = Breed::factory()->create(['species' => 'dog']);
    $pet = Pet::factory()->create(['user_id' => $user->id, 'breed_id' => $breed->id]);
    $vaccine = Vaccine::factory()->create(['species' => 'dog']);

    $response = $this->actingAs($user)->post(route('pets.vaccination-records.store', $pet), [
        'vaccine_id' => $vaccine->id,
        'administered_at' => '2026-01-15',
        'lot_number' => 'AB1234C',
    ]);

    $response->assertRedirect(route('pets.vaccination-records.index', $pet));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('vaccination_records', [
        'pet_id' => $pet->id,
        'vaccine_id' => $vaccine->id,
        'administered_at' => '2026-01-15 00:00:00',
        'lot_number' => 'AB1234C',
    ]);
});

test('a user can add a vaccination record with a custom vaccine name to their pet', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->post(route('pets.vaccination-records.store', $pet), [
        'custom_name' => 'Vaccin maison',
        'administered_at' => '2026-01-15',
    ]);

    $response->assertRedirect(route('pets.vaccination-records.index', $pet));

    $this->assertDatabaseHas('vaccination_records', [
        'pet_id' => $pet->id,
        'custom_name' => 'Vaccin maison',
        'vaccine_id' => null,
    ]);
});

test('adding a vaccination record fails with an empty payload', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->post(route('pets.vaccination-records.store', $pet), []);

    $response->assertSessionHasErrors(['vaccine_id', 'custom_name', 'administered_at']);
    $this->assertDatabaseEmpty('vaccination_records');
});

test('adding a vaccination record fails when the vaccine species does not match the pet', function () {
    $user = User::factory()->create();
    $breed = Breed::factory()->create(['species' => 'dog']);
    $pet = Pet::factory()->create(['user_id' => $user->id, 'breed_id' => $breed->id]);
    $catVaccine = Vaccine::factory()->create(['species' => 'cat']);

    $response = $this->actingAs($user)->post(route('pets.vaccination-records.store', $pet), [
        'vaccine_id' => $catVaccine->id,
        'administered_at' => '2026-01-15',
    ]);

    $response->assertSessionHasErrors([
        'vaccine_id' => 'La valeur sélectionnée pour vaccine id est invalide.',
    ]);
    $this->assertDatabaseEmpty('vaccination_records');
});

test('adding a vaccination record fails when the next reminder is before the administration date', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);
    $vaccine = Vaccine::factory()->create();

    $response = $this->actingAs($user)->post(route('pets.vaccination-records.store', $pet), [
        'vaccine_id' => $vaccine->id,
        'administered_at' => '2026-01-15',
        'next_due_at' => '2026-01-10',
    ]);

    $response->assertSessionHasErrors([
        'next_due_at' => 'Le champ next due at doit être une date postérieure ou égale à administered at.',
    ]);
    $this->assertDatabaseEmpty('vaccination_records');
});

test('adding a vaccination record fails when the administration date is in the future', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);
    $vaccine = Vaccine::factory()->create();

    $response = $this->actingAs($user)->post(route('pets.vaccination-records.store', $pet), [
        'vaccine_id' => $vaccine->id,
        'administered_at' => now()->addDay()->format('Y-m-d'),
    ]);

    $response->assertSessionHasErrors([
        'administered_at' => 'Le champ administered at doit être une date antérieure ou égale à today.',
    ]);
    $this->assertDatabaseEmpty('vaccination_records');
});

test('a user cannot add a vaccination record to another user pet', function () {
    $pet = Pet::factory()->create();
    $vaccine = Vaccine::factory()->create();

    $response = $this->actingAs(User::factory()->create())->post(route('pets.vaccination-records.store', $pet), [
        'vaccine_id' => $vaccine->id,
        'administered_at' => '2026-01-15',
    ]);

    $response->assertForbidden();
    $this->assertDatabaseEmpty('vaccination_records');
});

test('a user cannot see another user pet vaccination records', function () {
    $pet = Pet::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('pets.vaccination-records.index', $pet))
        ->assertForbidden();
});

test('a user cannot open the form to add a vaccination record to another user pet', function () {
    $pet = Pet::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('pets.vaccination-records.create', $pet))
        ->assertForbidden();
});

test('a user can open the form to edit their vaccination record', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);
    $vaccinationRecord = VaccinationRecord::factory()->for($pet)->create();

    $this->actingAs($user)
        ->get(route('vaccination-records.edit', $vaccinationRecord))
        ->assertOk();
});

test('a user can update their vaccination record', function () {
    $user = User::factory()->create();
    $breed = Breed::factory()->create(['species' => 'dog']);
    $pet = Pet::factory()->create(['user_id' => $user->id, 'breed_id' => $breed->id]);
    $vaccinationRecord = VaccinationRecord::factory()->for($pet)->create();
    $vaccine = Vaccine::factory()->create(['species' => 'dog']);

    $response = $this->actingAs($user)->patch(route('vaccination-records.update', $vaccinationRecord), [
        'vaccine_id' => $vaccine->id,
        'administered_at' => '2026-01-20',
        'lot_number' => 'XY42',
    ]);

    $response->assertRedirect(route('pets.vaccination-records.index', $pet));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('vaccination_records', [
        'id' => $vaccinationRecord->id,
        'vaccine_id' => $vaccine->id,
        'administered_at' => '2026-01-20 00:00:00',
        'lot_number' => 'XY42',
    ]);
});

test('a user cannot update another user vaccination record', function () {
    $vaccinationRecord = VaccinationRecord::factory()->create();

    $response = $this->actingAs(User::factory()->create())->patch(route('vaccination-records.update', $vaccinationRecord), [
        'administered_at' => '2026-01-20',
    ]);

    $response->assertForbidden();
    $this->assertDatabaseMissing('vaccination_records', [
        'id' => $vaccinationRecord->id,
        'administered_at' => '2026-01-20 00:00:00',
    ]);
});

test('deleting a vaccination record goes through a confirmation dialog', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);
    $record = VaccinationRecord::factory()->for($pet)->create();

    $this->actingAs($user)
        ->get(route('pets.vaccination-records.index', $pet))
        ->assertSee('data-dialog-open="delete-vaccination-record-'.$record->id.'"', false)
        ->assertSee('<dialog id="delete-vaccination-record-'.$record->id.'"', false);
});

test('a user can delete a vaccination record from their pet', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);
    $vaccinationRecord = VaccinationRecord::factory()->for($pet)->create();

    $response = $this->actingAs($user)->delete(route('vaccination-records.destroy', $vaccinationRecord));

    $response->assertRedirect(route('pets.vaccination-records.index', $pet));
    $response->assertSessionHas('success');
    $this->assertDatabaseMissing('vaccination_records', ['id' => $vaccinationRecord->id]);
});

test('a user cannot delete a vaccination record from another user pet', function () {
    $pet = Pet::factory()->create();
    $vaccinationRecord = VaccinationRecord::factory()->for($pet)->create();

    $response = $this->actingAs(User::factory()->create())
        ->delete(route('vaccination-records.destroy', $vaccinationRecord));

    $response->assertForbidden();
    $this->assertDatabaseHas('vaccination_records', ['id' => $vaccinationRecord->id]);
});

test('the list groups the injections of a vaccine and counts them in the header', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);
    $rage = Vaccine::factory()->create(['name' => 'Rage']);
    $lepto = Vaccine::factory()->create(['name' => 'Leptospirose']);

    VaccinationRecord::factory()->for($pet)->create([
        'vaccine_id' => $rage->id,
        'administered_at' => '2022-01-10',
        'next_due_at' => '2025-01-10',
    ]);
    VaccinationRecord::factory()->for($pet)->create([
        'vaccine_id' => $rage->id,
        'administered_at' => '2025-01-10',
        'next_due_at' => now()->addYears(2)->format('Y-m-d'),
    ]);
    VaccinationRecord::factory()->for($pet)->create([
        'vaccine_id' => $lepto->id,
        'administered_at' => '2025-08-02',
        'next_due_at' => now()->subMonth()->format('Y-m-d'),
    ]);

    $this->actingAs($user)
        ->get(route('pets.vaccination-records.index', $pet))
        ->assertOk()
        ->assertSee('3 enregistrements · 2 vaccins · 1 rappel dépassé')
        ->assertSee('2 injections', false)
        ->assertSee('Rage · injection précédente')
        ->assertSee('Remplacée')
        ->assertSeeInOrder(['Leptospirose', 'Rage']) // le rappel dépassé en tête
        ->assertSeeInOrder(['Vaccin', 'Fait le', 'Rappel', 'Statut']); // en-têtes du tableau bureau
});
