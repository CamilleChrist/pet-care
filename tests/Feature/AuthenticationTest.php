<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Password;

test('a user can register', function () {
    $response = $this->post('/register', [
        'name' => 'Camille',
        'email' => 'camille@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', ['email' => 'camille@example.com']);
});

test('registering cannot grant the admin role', function () {
    $this->post('/register', [
        'name' => 'Camille',
        'email' => 'camille@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => UserRole::Admin->value,
    ]);

    expect(User::firstWhere('email', 'camille@example.com')->role)->toBe(UserRole::User);
});

test('registration fails with invalid fields', function () {
    $response = $this->post('/register', [
        'name' => '',
        'email' => 'not-an-email',
        'password' => 'abc',
        'password_confirmation' => 'something-else',
    ]);

    $response->assertSessionHasErrors(['name', 'email', 'password']);
    $this->assertGuest();
});

test('registration fails when the email is already used', function () {
    User::factory()->create(['email' => 'camille@example.com']);

    $response = $this->post('/register', [
        'name' => 'Camille',
        'email' => 'camille@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('a user can login with correct credentials', function () {
    User::factory()->create([
        'email' => 'camille@example.com',
        'password' => 'password',
    ]);

    $response = $this->post('/login', [
        'email' => 'camille@example.com',
        'password' => 'password',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
});

test('login fails with a wrong password', function () {
    User::factory()->create([
        'email' => 'camille@example.com',
        'password' => 'password',
    ]);

    $response = $this->post('/login', [
        'email' => 'camille@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors(['email' => 'Les identifiants sont incorrects.']);
    $this->assertGuest();
});

test('forgot password works for an existing email', function () {
    User::factory()->create(['email' => 'camille@example.com']);

    $response = $this->post('/forgot-password', [
        'email' => 'camille@example.com',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertSessionHas('status');
});

test('forgot password fails for an unknown email', function () {
    $response = $this->post('/forgot-password', [
        'email' => 'unknown@example.com',
    ]);

    $response->assertSessionHasErrors('email');
});

test('a user can reset their password with a valid link', function () {
    $user = User::factory()->create(['email' => 'camille@example.com']);
    $token = Password::createToken($user);

    $response = $this->post('/reset-password', [
        'token' => $token,
        'email' => 'camille@example.com',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHasNoErrors();

    $this->post('/login', [
        'email' => 'camille@example.com',
        'password' => 'new-password',
    ])->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
});

test('password reset fails with an invalid link', function () {
    User::factory()->create(['email' => 'camille@example.com']);

    $response = $this->post('/reset-password', [
        'token' => 'invalid-token',
        'email' => 'camille@example.com',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('a logged in user can log out', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertRedirect('/');
    $this->assertGuest();
});
