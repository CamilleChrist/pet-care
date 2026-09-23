<?php

use App\Models\Pet;
use App\Models\User;
use App\View\Components\Nav\Breadcrumb;

test('the breadcrumb follows the route hierarchy up to the dashboard', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id, 'name' => 'Choupette']);

    $this->actingAs($user)->get(route('pets.weight-records.create', $pet))->assertOk();

    expect((new Breadcrumb)->render()->getData()['items'])->toBe([
        ['label' => 'Animaux', 'url' => route('pets.index')],
        ['label' => 'Choupette', 'url' => route('pets.show', $pet)],
        ['label' => 'Nouvelle pesée', 'url' => null],
    ]);
});

test('the breadcrumb is empty on an unmapped route', function () {
    $this->get(route('login'))->assertOk();

    expect((new Breadcrumb)->render()->getData()['items'])->toBe([]);
});
