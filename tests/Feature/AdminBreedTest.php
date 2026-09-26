<?php

use App\Models\Breed;
use App\Models\Pet;
use App\Models\User;

beforeEach(fn () => $this->admin = User::factory()->admin()->create());

test('an admin sees the breeds with how many pets use them', function () {
    $breed = Breed::factory()->create(['name' => 'Berger australien', 'species' => 'dog']);
    Pet::factory()->count(2)->create(['breed_id' => $breed->id]);

    $this->actingAs($this->admin)
        ->get(route('admin.breeds.index'))
        ->assertOk()
        ->assertSee('Berger australien');
});

test('an admin can create a breed', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.breeds.store'), ['name' => 'Berger australien', 'species' => 'dog'])
        ->assertRedirect(route('admin.breeds.index'));

    $this->assertDatabaseHas('breeds', ['name' => 'Berger australien', 'species' => 'dog']);
});

test('the same breed name cannot be created twice for one species', function () {
    Breed::factory()->create(['name' => 'Berger australien', 'species' => 'dog']);

    $this->actingAs($this->admin)
        ->post(route('admin.breeds.store'), ['name' => 'Berger australien', 'species' => 'dog'])
        ->assertSessionHasErrors('name');
});

test('the same breed name is allowed across two species', function () {
    Breed::factory()->create(['name' => 'Bleu russe', 'species' => 'dog']);

    $this->actingAs($this->admin)
        ->post(route('admin.breeds.store'), ['name' => 'Bleu russe', 'species' => 'cat'])
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('breeds', ['name' => 'Bleu russe', 'species' => 'cat']);
});

test('renaming a breed does not collide with itself', function () {
    $breed = Breed::factory()->create(['name' => 'Berger australien', 'species' => 'dog']);

    $this->actingAs($this->admin)
        ->patch(route('admin.breeds.update', $breed), ['name' => 'Berger australien', 'species' => 'dog'])
        ->assertSessionHasNoErrors();
});

test('deleting a breed leaves its pets without one', function () {
    $breed = Breed::factory()->create();
    $pet = Pet::factory()->create(['breed_id' => $breed->id]);

    $this->actingAs($this->admin)
        ->delete(route('admin.breeds.destroy', $breed))
        ->assertRedirect(route('admin.breeds.index'));

    $this->assertDatabaseMissing('breeds', ['id' => $breed->id]);
    expect($pet->refresh())->breed_id->toBeNull();
});
