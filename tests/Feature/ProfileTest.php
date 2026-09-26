<?php

use App\Models\Pet;
use App\Models\User;
use App\Models\WeightRecord;
use Illuminate\Support\Facades\Hash;

test('a user can see their profile', function () {
    $user = User::factory()->create(['name' => 'Camille Marchand', 'email' => 'camille@example.fr']);

    $this->actingAs($user)->get(route('profile.edit'))
        ->assertOk()
        ->assertSee('Camille Marchand')
        ->assertSee('camille@example.fr');
});

test('a user can see the password form', function () {
    $this->actingAs(User::factory()->create())->get(route('profile.password.edit'))
        ->assertOk()
        ->assertSee('Mot de passe actuel');
});

test('a user can update their name and email', function () {
    $user = User::factory()->create(['name' => 'Camille', 'email' => 'camille@example.fr']);

    $this->actingAs($user)->patch(route('profile.update'), [
        'name' => 'Camille Marchand',
        'email' => 'nouvelle@example.fr',
    ])->assertRedirect(route('profile.edit'));

    expect($user->refresh()->only('name', 'email'))
        ->toBe(['name' => 'Camille Marchand', 'email' => 'nouvelle@example.fr']);
});

test('the profile update fails when the email is already used', function () {
    $user = User::factory()->create(['email' => 'camille@example.fr']);
    User::factory()->create(['email' => 'occupe@example.fr']);

    $this->actingAs($user)->patch(route('profile.update'), [
        'name' => 'Camille',
        'email' => 'occupe@example.fr',
    ])->assertSessionHasErrors('email');

    expect($user->refresh()->email)->toBe('camille@example.fr');
});

test('a user can change their password', function () {
    $user = User::factory()->create(['password' => 'ancien-mot-de-passe']);

    $this->actingAs($user)->patch(route('profile.password.update'), [
        'current_password' => 'ancien-mot-de-passe',
        'password' => 'nouveau-mot-de-passe',
        'password_confirmation' => 'nouveau-mot-de-passe',
    ])->assertRedirect(route('profile.edit'));

    expect(Hash::check('nouveau-mot-de-passe', $user->refresh()->password))->toBeTrue();
});

test('the password change fails when the current password is wrong', function () {
    $user = User::factory()->create(['password' => 'ancien-mot-de-passe']);

    $this->actingAs($user)->patch(route('profile.password.update'), [
        'current_password' => 'pas-le-bon',
        'password' => 'nouveau-mot-de-passe',
        'password_confirmation' => 'nouveau-mot-de-passe',
    ])->assertSessionHasErrors('current_password');

    expect(Hash::check('ancien-mot-de-passe', $user->refresh()->password))->toBeTrue();
});

test('deleting the account removes the pets and their records', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);
    $record = WeightRecord::factory()->create(['pet_id' => $pet->id]);

    $this->actingAs($user)->delete(route('profile.destroy'))->assertRedirect(route('login'));

    $this->assertGuest();
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
    $this->assertDatabaseMissing('pets', ['id' => $pet->id]);
    $this->assertDatabaseMissing('weight_records', ['id' => $record->id]);
});

test('guests cannot access the profile', function () {
    $this->get(route('profile.edit'))->assertRedirect(route('login'));
    $this->get(route('profile.password.edit'))->assertRedirect(route('login'));
    $this->patch(route('profile.update'))->assertRedirect(route('login'));
    $this->patch(route('profile.password.update'))->assertRedirect(route('login'));
    $this->delete(route('profile.destroy'))->assertRedirect(route('login'));
    $this->patch(route('profile.notifications.update'))->assertRedirect(route('login'));
    $this->post(route('profile.push-subscription.store'))->assertRedirect(route('login'));
});

test('a user can turn the reminders by mail on and off', function (bool $before, array $payload, bool $after) {
    $user = User::factory()->create(['mail_notifications' => $before]);

    $this->actingAs($user)->patch(route('profile.notifications.update'), $payload)->assertNoContent();

    expect($user->refresh()->mail_notifications)->toBe($after);
})->with([
    // Un switch décoché n'envoie rien : l'absence du champ vaut « désactivé ».
    'off' => [true, [], false],
    'on' => [false, ['mail_notifications' => '1'], true],
]);

test('a user can register the push subscription of their device', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->postJson(route('profile.push-subscription.store'), [
        'endpoint' => 'https://fcm.googleapis.com/fcm/send/abc123',
        'key' => 'BNcRdreALRFXTkOOUHK1EtK2wtaz5Ry4YfYCA_0QTpQtUbVlUls0VJXg7A8u-Ts1XbjhazAkj7I99e8QcYP7DkM',
        'token' => 'tBHItJI5svbpez7KI4CCXg',
        'encoding' => 'aes128gcm',
    ])->assertNoContent();

    $this->assertDatabaseHas('push_subscriptions', [
        'subscribable_type' => User::class,
        'subscribable_id' => $user->id,
        'endpoint' => 'https://fcm.googleapis.com/fcm/send/abc123',
        'public_key' => 'BNcRdreALRFXTkOOUHK1EtK2wtaz5Ry4YfYCA_0QTpQtUbVlUls0VJXg7A8u-Ts1XbjhazAkj7I99e8QcYP7DkM',
        'auth_token' => 'tBHItJI5svbpez7KI4CCXg',
        'content_encoding' => 'aes128gcm',
    ]);
});

test('a push subscription needs an endpoint and its keys', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->postJson(route('profile.push-subscription.store'), [])
        ->assertJsonValidationErrors(['endpoint', 'key', 'token']);

    $this->assertDatabaseEmpty('push_subscriptions');
});

test('a push subscription is refused when its endpoint is not https', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->postJson(route('profile.push-subscription.store'), [
        'endpoint' => 'http://push.example.com/abc123',
        'key' => 'BNcRdreALRFXTkOOUHK1EtK2wtaz5Ry4YfYCA_0QTpQtUbVlUls0VJXg7A8u-Ts1XbjhazAkj7I99e8QcYP7DkM',
        'token' => 'tBHItJI5svbpez7KI4CCXg',
    ])->assertJsonValidationErrors('endpoint');

    $this->assertDatabaseEmpty('push_subscriptions');
});
