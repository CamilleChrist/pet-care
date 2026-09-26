<?php

use App\Models\Pet;
use App\Models\User;

test('a guest is redirected to login', function (string $route) {
    $this->get(route($route))->assertRedirect(route('login'));
})->with('admin routes');

test('a regular user is forbidden', function (string $route) {
    $this->actingAs(User::factory()->create())
        ->get(route($route))
        ->assertForbidden();
})->with('admin routes');

test('an admin can reach the page', function (string $route) {
    $this->actingAs(User::factory()->admin()->create())
        ->get(route($route))
        ->assertOk();
})->with('admin routes');

test('the admin entry only shows in the nav for an admin', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('dashboard'))
        ->assertDontSee(route('admin.users.index'));

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('dashboard'))
        ->assertSee(route('admin.users.index'));
});

test('the back-office root lands on the user list', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.home'))
        ->assertRedirect(route('admin.users.index'));
});

dataset('admin routes', [
    'users index' => ['admin.users.index'],
    'pets index' => ['admin.pets.index'],
    'pets create' => ['admin.pets.create'],
]);

test('a listing offers delete for every row but the admin own account', function () {
    $admin = User::factory()->admin()->create();
    $other = User::factory()->create();

    $response = $this->actingAs($admin)->get(route('admin.users.index'));

    $response->assertSee('delete-user-'.$other->id)
        ->assertDontSee('delete-user-'.$admin->id);
});

test('every row action carries an accessible name', function () {
    $pet = Pet::factory()->create(['name' => 'Moustache']);

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.pets.index'))
        ->assertSee('Modifier Moustache')
        ->assertSee('Supprimer Moustache');
});
