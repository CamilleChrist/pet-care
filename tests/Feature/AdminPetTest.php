<?php

use App\Enums\PetGender;
use App\Models\Breed;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

test('an admin sees every pet with its owner', function () {
    $owner = User::factory()->create(['name' => 'Camille']);
    Pet::factory()->create(['user_id' => $owner->id, 'name' => 'Moustache']);

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.pets.index'))
        ->assertOk()
        ->assertSee('Moustache')
        ->assertSee('Camille');
});

test('an admin can filter pets by owner', function () {
    $owner = User::factory()->create();
    Pet::factory()->create(['user_id' => $owner->id, 'name' => 'Moustache']);
    Pet::factory()->create(['user_id' => User::factory()->create()->id, 'name' => 'Pistache']);

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.pets.index', ['user' => $owner->id]))
        ->assertOk()
        ->assertSee('Moustache')
        ->assertDontSee('Pistache');
});

test('an admin can create a pet for another user', function () {
    $owner = User::factory()->create();
    $breed = Breed::factory()->create();

    $this->actingAs(User::factory()->admin()->create())
        ->post(route('admin.pets.store'), [
            'user_id' => $owner->id,
            'breed_id' => $breed->id,
            'name' => 'Moustache',
            'gender' => PetGender::Female->value,
            'birth_date' => '2024-01-15',
        ])
        ->assertRedirect(route('admin.pets.index'));

    $this->assertDatabaseHas('pets', ['name' => 'Moustache', 'user_id' => $owner->id]);
});

test('creating a pet requires an existing owner', function () {
    $breed = Breed::factory()->create();

    $this->actingAs(User::factory()->admin()->create())
        ->post(route('admin.pets.store'), [
            'user_id' => 9999,
            'breed_id' => $breed->id,
            'name' => 'Moustache',
            'gender' => PetGender::Female->value,
            'birth_date' => '2024-01-15',
        ])
        ->assertSessionHasErrors('user_id');
});

test('an admin can move a pet to another owner', function () {
    $pet = Pet::factory()->create();
    $newOwner = User::factory()->create();

    $this->actingAs(User::factory()->admin()->create())
        ->patch(route('admin.pets.update', $pet), [
            'user_id' => $newOwner->id,
            'breed_id' => $pet->breed_id,
            'name' => 'Moustache',
            'gender' => $pet->gender->value,
            'birth_date' => $pet->birth_date,
        ])
        ->assertRedirect(route('admin.pets.index'));

    expect($pet->refresh())->user_id->toBe($newOwner->id)->name->toBe('Moustache');
});

test('an admin can remove a pet photo', function () {
    Storage::fake('public');

    $pet = Pet::factory()->create(['photo_path' => 'pets/moustache.jpg']);
    Storage::disk('public')->put('pets/moustache.jpg', 'fake');

    $this->actingAs(User::factory()->admin()->create())
        ->patch(route('admin.pets.update', $pet), [
            'user_id' => $pet->user_id,
            'breed_id' => $pet->breed_id,
            'name' => $pet->name,
            'gender' => $pet->gender->value,
            'birth_date' => $pet->birth_date,
            'remove_photo' => '1',
        ]);

    expect($pet->refresh()->photo_path)->toBeNull();
    Storage::disk('public')->assertMissing('pets/moustache.jpg');
});

test('deleting a pet also deletes its photo file', function () {
    Storage::fake('public');

    $pet = Pet::factory()->create(['photo_path' => 'pets/moustache.jpg']);
    Storage::disk('public')->put('pets/moustache.jpg', 'fake');

    $this->actingAs(User::factory()->admin()->create())
        ->delete(route('admin.pets.destroy', $pet))
        ->assertRedirect(route('admin.pets.index'));

    $this->assertDatabaseMissing('pets', ['id' => $pet->id]);
    Storage::disk('public')->assertMissing('pets/moustache.jpg');
});

test('a regular user cannot reach the pet management routes', function () {
    $pet = Pet::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('admin.pets.index'))->assertForbidden();
    $this->actingAs($user)->get(route('admin.pets.edit', $pet))->assertForbidden();
    $this->actingAs($user)->delete(route('admin.pets.destroy', $pet))->assertForbidden();
});
