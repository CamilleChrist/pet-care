<?php

use App\Models\User;
use App\Models\VaccinationRecord;
use App\Models\Vaccine;

beforeEach(fn () => $this->admin = User::factory()->admin()->create());

test('an admin sees the vaccines', function () {
    $vaccine = Vaccine::factory()->create(['name' => 'Rage', 'species' => 'dog']);

    $this->actingAs($this->admin)
        ->get(route('admin.vaccines.index'))
        ->assertOk()
        ->assertSee('Rage')
        ->assertSee(route('admin.vaccines.edit', $vaccine));
});

test('an admin can create a vaccine', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.vaccines.store'), [
            'name' => 'Rage',
            'species' => 'dog',
            'description' => 'Rappel tous les trois ans.',
        ])
        ->assertRedirect(route('admin.vaccines.index'));

    $this->assertDatabaseHas('vaccines', ['name' => 'Rage', 'species' => 'dog']);
});

test('a vaccine needs a species the app knows', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.vaccines.store'), ['name' => 'Rage', 'species' => 'horse'])
        ->assertSessionHasErrors('species');
});

test('an admin can update a vaccine', function () {
    $vaccine = Vaccine::factory()->create(['name' => 'Rage']);

    $this->actingAs($this->admin)
        ->patch(route('admin.vaccines.update', $vaccine), [
            'name' => 'Rage (renforcé)',
            'species' => $vaccine->species,
        ])
        ->assertRedirect(route('admin.vaccines.index'));

    expect($vaccine->refresh())->name->toBe('Rage (renforcé)');
});

test('deleting a vaccine keeps the injections already recorded', function () {
    $vaccine = Vaccine::factory()->create();
    $record = VaccinationRecord::factory()->create(['vaccine_id' => $vaccine->id]);

    $this->actingAs($this->admin)
        ->delete(route('admin.vaccines.destroy', $vaccine))
        ->assertRedirect(route('admin.vaccines.index'));

    $this->assertDatabaseHas('vaccination_records', ['id' => $record->id]);
    expect($record->refresh())->vaccine_id->toBeNull();
});
