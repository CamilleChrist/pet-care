<?php

use App\Enums\UserRole;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

test('an admin sees every registered user', function () {
    $other = User::factory()->create(['name' => 'Camille']);

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.users.index'))
        ->assertOk()
        ->assertSee('Camille')
        ->assertSee($other->email);
});

test('an admin sees the pets of a user', function () {
    $other = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $other->id, 'name' => 'Moustache']);

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.users.show', $other))
        ->assertOk()
        ->assertSee('Moustache');
});

test('an admin can update a user and promote them', function () {
    $other = User::factory()->create();

    $this->actingAs(User::factory()->admin()->create())
        ->patch(route('admin.users.update', $other), [
            'name' => 'Camille',
            'email' => 'camille@example.com',
            'role' => UserRole::Admin->value,
        ])
        ->assertRedirect(route('admin.users.show', $other));

    expect($other->refresh())
        ->name->toBe('Camille')
        ->email->toBe('camille@example.com')
        ->role->toBe(UserRole::Admin);
});

test('an admin cannot give a user an email that is already taken', function () {
    $taken = User::factory()->create(['email' => 'taken@example.com']);
    $other = User::factory()->create();

    $this->actingAs(User::factory()->admin()->create())
        ->patch(route('admin.users.update', $other), [
            'name' => $other->name,
            'email' => $taken->email,
            'role' => UserRole::User->value,
        ])
        ->assertSessionHasErrors('email');
});

test('deleting a user removes their pets and photo files', function () {
    Storage::fake('public');

    $other = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $other->id, 'photo_path' => 'pets/moustache.jpg']);
    Storage::disk('public')->put('pets/moustache.jpg', 'fake');

    $this->actingAs(User::factory()->admin()->create())
        ->delete(route('admin.users.destroy', $other))
        ->assertRedirect(route('admin.users.index'));

    $this->assertDatabaseMissing('users', ['id' => $other->id]);
    $this->assertDatabaseMissing('pets', ['id' => $pet->id]);
    Storage::disk('public')->assertMissing('pets/moustache.jpg');
});

test('an admin cannot delete their own account from the back-office', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->delete(route('admin.users.destroy', $admin))
        ->assertForbidden();

    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});

test('a regular user cannot reach the user management routes', function () {
    $other = User::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('admin.users.show', $other))->assertForbidden();
    $this->actingAs($user)->get(route('admin.users.edit', $other))->assertForbidden();
    $this->actingAs($user)->patch(route('admin.users.update', $other), [])->assertForbidden();
    $this->actingAs($user)->delete(route('admin.users.destroy', $other))->assertForbidden();
});
