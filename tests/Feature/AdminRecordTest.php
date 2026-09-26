<?php

use App\Models\Breed;
use App\Models\Pet;
use App\Models\User;
use App\Models\VaccinationRecord;
use App\Models\Vaccine;
use App\Models\WeightRecord;

beforeEach(fn () => $this->admin = User::factory()->admin()->create());

test('an admin sees every vaccination with its pet and owner', function () {
    $owner = User::factory()->create(['name' => 'Camille']);
    $pet = Pet::factory()->create(['user_id' => $owner->id, 'name' => 'Moustache']);
    VaccinationRecord::factory()->create(['pet_id' => $pet->id]);

    $this->actingAs($this->admin)
        ->get(route('admin.pets.show', $pet))
        ->assertOk()
        ->assertSee('Moustache')
        ->assertSee('Camille');
});

test('the pet page lists its vaccinations and weighings with their actions', function () {
    $pet = Pet::factory()->create();
    $vaccination = VaccinationRecord::factory()->create([
        'pet_id' => $pet->id,
        'vaccine_id' => null,
        'custom_name' => 'Vaccin hors catalogue',
    ]);
    $weighing = WeightRecord::factory()->create(['pet_id' => $pet->id, 'weight' => 21.8]);

    $this->actingAs($this->admin)
        ->get(route('admin.pets.show', $pet))
        ->assertOk()
        ->assertSee('Vaccin hors catalogue')
        ->assertSee('21,8')
        ->assertSee(route('admin.vaccination-records.edit', $vaccination))
        ->assertSee(route('admin.weight-records.edit', $weighing))
        ->assertSee('delete-vaccination-record-'.$vaccination->id)
        ->assertSee('delete-weight-record-'.$weighing->id);
});

test('adding a record from a pet page preselects that pet', function () {
    $pet = Pet::factory()->create();

    $this->actingAs($this->admin)
        ->get(route('admin.weight-records.create', ['pet' => $pet->id]))
        ->assertOk()
        ->assertSee('value="'.$pet->id.'" selected', false);
});

test('an admin can record a vaccination for any pet', function () {
    $pet = Pet::factory()->create();
    $vaccine = Vaccine::factory()->create();

    $this->actingAs($this->admin)
        ->post(route('admin.vaccination-records.store'), [
            'pet_id' => $pet->id,
            'vaccine_id' => $vaccine->id,
            'administered_at' => now()->subMonth()->format('Y-m-d'),
            'next_due_at' => now()->addYear()->format('Y-m-d'),
        ])
        ->assertRedirect(route('admin.pets.show', $pet));

    $this->assertDatabaseHas('vaccination_records', ['pet_id' => $pet->id, 'vaccine_id' => $vaccine->id]);
});

test('a vaccination needs either a vaccine or a free name', function () {
    $pet = Pet::factory()->create();

    $this->actingAs($this->admin)
        ->post(route('admin.vaccination-records.store'), [
            'pet_id' => $pet->id,
            'administered_at' => now()->subMonth()->format('Y-m-d'),
        ])
        ->assertSessionHasErrors(['vaccine_id', 'custom_name']);
});

test('a reminder cannot fall before the injection', function () {
    $pet = Pet::factory()->create();

    $this->actingAs($this->admin)
        ->post(route('admin.vaccination-records.store'), [
            'pet_id' => $pet->id,
            'custom_name' => 'Vaccin hors catalogue',
            'administered_at' => now()->subMonth()->format('Y-m-d'),
            'next_due_at' => now()->subYear()->format('Y-m-d'),
        ])
        ->assertSessionHasErrors('next_due_at');
});

test('an admin can move a vaccination to another pet', function () {
    $record = VaccinationRecord::factory()->create();
    $otherPet = Pet::factory()->create();

    $this->actingAs($this->admin)
        ->patch(route('admin.vaccination-records.update', $record), [
            'pet_id' => $otherPet->id,
            'custom_name' => 'Vaccin hors catalogue',
            'administered_at' => now()->subMonth()->format('Y-m-d'),
        ])
        ->assertRedirect(route('admin.pets.show', $otherPet));

    expect($record->refresh())->pet_id->toBe($otherPet->id);
});

test('an admin can delete a vaccination', function () {
    $record = VaccinationRecord::factory()->create();

    $this->actingAs($this->admin)
        ->delete(route('admin.vaccination-records.destroy', $record))
        ->assertRedirect(route('admin.pets.show', $record->pet_id));

    $this->assertDatabaseMissing('vaccination_records', ['id' => $record->id]);
});

test('an admin sees every weighing with its pet and owner', function () {
    $owner = User::factory()->create(['name' => 'Camille']);
    $pet = Pet::factory()->create(['user_id' => $owner->id, 'name' => 'Moustache']);
    WeightRecord::factory()->create(['pet_id' => $pet->id]);

    $this->actingAs($this->admin)
        ->get(route('admin.pets.show', $pet))
        ->assertOk()
        ->assertSee('Moustache')
        ->assertSee('Camille');
});

test('an admin can record a weighing for any pet', function () {
    $pet = Pet::factory()->create();

    $this->actingAs($this->admin)
        ->post(route('admin.weight-records.store'), [
            'pet_id' => $pet->id,
            'weight' => 21.8,
            'recorded_at' => now()->subDay()->format('Y-m-d\TH:i'),
        ])
        ->assertRedirect(route('admin.pets.show', $pet));

    $this->assertDatabaseHas('weight_records', ['pet_id' => $pet->id, 'weight' => 21.8]);
});

test('a weighing cannot be recorded in the future', function () {
    $pet = Pet::factory()->create();

    $this->actingAs($this->admin)
        ->post(route('admin.weight-records.store'), [
            'pet_id' => $pet->id,
            'weight' => 21.8,
            'recorded_at' => now()->addWeek()->format('Y-m-d\TH:i'),
        ])
        ->assertSessionHasErrors('recorded_at');
});

test('an admin can correct and delete a weighing', function () {
    $record = WeightRecord::factory()->create(['weight' => 21.8]);

    $this->actingAs($this->admin)
        ->patch(route('admin.weight-records.update', $record), [
            'pet_id' => $record->pet_id,
            'weight' => 22.4,
            'recorded_at' => $record->recorded_at->format('Y-m-d\TH:i'),
        ])
        ->assertRedirect(route('admin.pets.show', $record->pet_id));

    expect((float) $record->refresh()->weight)->toBe(22.4);

    $this->actingAs($this->admin)
        ->delete(route('admin.weight-records.destroy', $record))
        ->assertRedirect(route('admin.pets.show', $record->pet_id));

    $this->assertDatabaseMissing('weight_records', ['id' => $record->id]);
});

test('a regular user cannot reach the record management routes', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('admin.vaccination-records.create'))->assertForbidden();
    $this->actingAs($user)->get(route('admin.weight-records.create'))->assertForbidden();
});

test('only resources with a page get the view icon', function () {
    $pet = Pet::factory()->create();
    $breed = Breed::factory()->create();

    $this->actingAs($this->admin)
        ->get(route('admin.pets.index'))
        ->assertSee(route('admin.pets.show', $pet));

    $this->actingAs($this->admin)
        ->get(route('admin.breeds.index'))
        ->assertDontSee('Voir '.$breed->name);
});
