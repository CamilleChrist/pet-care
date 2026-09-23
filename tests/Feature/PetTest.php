<?php

use App\Models\Breed;
use App\Models\Pet;
use App\Models\User;
use App\Models\VaccinationRecord;
use App\Models\WeightRecord;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('guests cannot access pets', function () {
    $pet = Pet::factory()->create();

    $this->get(route('pets.index'))->assertRedirect(route('login'));
    $this->get(route('pets.create'))->assertRedirect(route('login'));
    $this->post(route('pets.store'))->assertRedirect(route('login'));
    $this->get(route('pets.show', $pet))->assertRedirect(route('login'));
    $this->get(route('pets.edit', $pet))->assertRedirect(route('login'));
    $this->patch(route('pets.update', $pet))->assertRedirect(route('login'));
    $this->delete(route('pets.destroy', $pet))->assertRedirect(route('login'));
});

test('a user sees their pets in the list', function () {
    $user = User::factory()->create();
    Pet::factory()->create(['user_id' => $user->id, 'name' => 'Choupette']);

    $this->actingAs($user)
        ->get(route('pets.index'))
        ->assertOk()
        ->assertSee('Choupette');
});

test('the pet list shows the latest weight and the closest booster of each pet', function () {
    $this->travelTo('2026-09-20');
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);
    WeightRecord::factory()->create(['pet_id' => $pet->id, 'weight' => 20.0, 'recorded_at' => '2026-08-01']);
    WeightRecord::factory()->create(['pet_id' => $pet->id, 'weight' => 21.8, 'recorded_at' => '2026-09-01']);
    // Le rappel de la première injection est remplacé par celui de la seconde, le second vaccin est à jour.
    $vaccine = VaccinationRecord::factory()->create(['pet_id' => $pet->id, 'administered_at' => '2025-09-01', 'next_due_at' => '2026-09-01'])->vaccine;
    VaccinationRecord::factory()->create(['pet_id' => $pet->id, 'vaccine_id' => $vaccine->id, 'administered_at' => '2026-09-01', 'next_due_at' => '2026-10-01']);
    VaccinationRecord::factory()->create(['pet_id' => $pet->id, 'administered_at' => '2026-09-01', 'next_due_at' => '2027-09-01']);

    $this->actingAs($user)
        ->get(route('pets.index'))
        ->assertOk()
        ->assertSee('21,8 kg')
        ->assertSee('Dans 11 jours')
        ->assertDontSee('En retard');
});

test('a user can create a pet', function () {
    $user = User::factory()->create();
    $breed = Breed::factory()->create();

    $response = $this->actingAs($user)->post(route('pets.store'), [
        'breed_id' => $breed->id,
        'name' => 'Choupette',
        'gender' => 'female',
        'birth_date' => '2020-05-12',
    ]);

    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('pets', [
        'user_id' => $user->id,
        'breed_id' => $breed->id,
        'name' => 'Choupette',
        'gender' => 'female',
        'birth_date' => '2020-05-12',
    ]);
});

test('the create form lists the breeds with their species', function () {
    $user = User::factory()->create();
    $breed = Breed::factory()->create(['name' => 'Berger Australien', 'species' => 'dog']);

    $this->actingAs($user)
        ->get(route('pets.create'))
        ->assertOk()
        ->assertSeeInOrder(['Identité', 'Santé', 'Photo'])
        // Le filtrage des races par espèce (pet-form.js) s'appuie sur cet attribut.
        ->assertSee('value="'.$breed->id.'" data-species="dog"', escape: false)
        ->assertSee('Berger Australien');
});

test('a user can create a pet with a photo', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $breed = Breed::factory()->create();

    $this->actingAs($user)->post(route('pets.store'), [
        'breed_id' => $breed->id,
        'name' => 'Choupette',
        'gender' => 'female',
        'birth_date' => '2020-05-12',
        'photo' => UploadedFile::fake()->image('choupette.jpg'),
    ]);

    $path = Pet::firstWhere('name', 'Choupette')->photo_path;

    expect($path)->not->toBeNull();
    Storage::disk('public')->assertExists($path);
});

test('creating a pet fails with invalid data', function () {
    $response = $this->actingAs(User::factory()->create())->post(route('pets.store'), [
        'breed_id' => 999,
        'name' => '',
        'gender' => 'alien',
        'birth_date' => 'not-a-date',
    ]);

    $response->assertSessionHasErrors(['breed_id', 'name', 'gender', 'birth_date']);
    $this->assertDatabaseEmpty('pets');
});

test('a user can see a pet', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id, 'name' => 'Choupette']);

    $this->actingAs($user)
        ->get(route('pets.show', $pet))
        ->assertOk()
        ->assertSee('Choupette')
        ->assertSee($pet->breed->name)
        ->assertSee($pet->gender->label())
        ->assertSee('Supprimer la fiche');
});

test('a user can open the edit form of a pet', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create([
        'user_id' => $user->id,
        'name' => 'Choupette',
        'birth_date' => '2020-05-12',
        'health_notes' => 'Croquettes sans céréales',
    ]);

    $this->actingAs($user)
        ->get(route('pets.edit', $pet))
        ->assertOk()
        ->assertSee('Choupette')
        ->assertSee('value="2020-05-12"', escape: false)
        ->assertSee('Croquettes sans céréales');
});

test('a user can update a pet', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id, 'name' => 'Choupette']);

    $response = $this->actingAs($user)->patch(route('pets.update', $pet), [
        'breed_id' => $pet->breed_id,
        'name' => 'Choupinette',
        'gender' => 'male',
        'birth_date' => '2021-01-02',
        'health_notes' => 'Vaccinee',
        'last_vet_visit_at' => '2026-01-15',
    ]);

    $response->assertRedirect(route('pets.show', $pet->id));

    $this->assertDatabaseHas('pets', [
        'id' => $pet->id,
        'name' => 'Choupinette',
        'gender' => 'male',
        'birth_date' => '2021-01-02',
        'health_notes' => 'Vaccinee',
        'last_vet_visit_at' => '2026-01-15',
    ]);
});

test('a user can upload a pet photo and see it', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id, 'name' => 'Choupette']);

    $this->actingAs($user)->patch(route('pets.update', $pet), [
        'breed_id' => $pet->breed_id,
        'name' => 'Choupette',
        'gender' => 'male',
        'birth_date' => '2021-01-02',
        'photo' => UploadedFile::fake()->image('choupette.jpg'),
    ]);

    $path = $pet->fresh()->photo_path;

    Storage::disk('public')->assertExists($path);

    $this->actingAs($user)
        ->get(route('pets.show', $pet))
        ->assertSee('/storage/'.$path, escape: false);
});

test('a user can remove a pet photo', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->patch(route('pets.update', $pet), [
        'breed_id' => $pet->breed_id,
        'name' => $pet->name,
        'gender' => $pet->gender->value,
        'birth_date' => $pet->birth_date,
        'photo' => UploadedFile::fake()->image('choupette.jpg'),
    ]);

    $path = $pet->fresh()->photo_path;

    $this->actingAs($user)->patch(route('pets.update', $pet), [
        'breed_id' => $pet->breed_id,
        'name' => $pet->name,
        'gender' => $pet->gender->value,
        'birth_date' => $pet->birth_date,
        'remove_photo' => 1,
    ]);

    Storage::disk('public')->assertMissing($path);
    expect($pet->fresh()->photo_path)->toBeNull();
});

test('replacing a pet photo removes the previous file', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->patch(route('pets.update', $pet), [
        'breed_id' => $pet->breed_id,
        'name' => $pet->name,
        'gender' => $pet->gender->value,
        'birth_date' => $pet->birth_date,
        'photo' => UploadedFile::fake()->image('choupette.jpg'),
    ]);

    $oldPath = $pet->fresh()->photo_path;

    $this->actingAs($user)->patch(route('pets.update', $pet), [
        'breed_id' => $pet->breed_id,
        'name' => $pet->name,
        'gender' => $pet->gender->value,
        'birth_date' => $pet->birth_date,
        'photo' => UploadedFile::fake()->image('choupette.png'),
    ]);

    $newPath = $pet->fresh()->photo_path;

    expect($newPath)->not->toBe($oldPath);
    Storage::disk('public')->assertMissing($oldPath);
    Storage::disk('public')->assertExists($newPath);
});

test('updating a pet shows an explicit message when the photo is too large to upload', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);

    $photo = new UploadedFile(
        UploadedFile::fake()->image('choupette.jpg')->getPathname(),
        'choupette.jpg',
        'image/jpeg',
        UPLOAD_ERR_INI_SIZE,
        true
    );

    $response = $this->actingAs($user)->patch(route('pets.update', $pet), [
        'breed_id' => $pet->breed_id,
        'name' => $pet->name,
        'gender' => $pet->gender->value,
        'birth_date' => $pet->birth_date,
        'photo' => $photo,
    ]);

    $response->assertSessionHasErrors([
        'photo' => 'La photo est trop volumineuse pour être envoyée (2 Mo maximum).',
    ]);
});

test('updating a pet fails with invalid data', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id, 'name' => 'Choupette']);

    $response = $this->actingAs($user)->patch(route('pets.update', $pet), [
        'breed_id' => $pet->breed_id,
        'name' => '',
        'gender' => 'alien',
        'birth_date' => 'not-a-date',
    ]);

    $response->assertSessionHasErrors(['name', 'gender', 'birth_date']);
    $this->assertDatabaseHas('pets', ['id' => $pet->id, 'name' => 'Choupette']);
});

test('a user can delete a pet', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->delete(route('pets.destroy', $pet));

    $response->assertRedirect(route('dashboard'));
    $this->assertDatabaseMissing('pets', ['id' => $pet->id]);
});

test('deleting a pet removes its photo from storage', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $pet = Pet::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->patch(route('pets.update', $pet), [
        'breed_id' => $pet->breed_id,
        'name' => $pet->name,
        'gender' => $pet->gender->value,
        'birth_date' => $pet->birth_date,
        'photo' => UploadedFile::fake()->image('choupette.jpg'),
    ]);

    $path = $pet->fresh()->photo_path;

    $this->actingAs($user)->delete(route('pets.destroy', $pet));

    Storage::disk('public')->assertMissing($path);
});

test('a user cannot see another user pet', function () {
    $pet = Pet::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('pets.show', $pet))
        ->assertForbidden();
});

test('a user cannot update another user pet', function () {
    $pet = Pet::factory()->create(['name' => 'Choupette']);

    $this->actingAs(User::factory()->create())
        ->patch(route('pets.update', $pet), [
            'breed_id' => $pet->breed_id,
            'name' => 'Vole',
            'gender' => 'male',
            'birth_date' => '2021-01-02',
        ])
        ->assertForbidden();

    $this->assertDatabaseHas('pets', ['id' => $pet->id, 'name' => 'Choupette']);
});

test('a user cannot delete another user pet', function () {
    $pet = Pet::factory()->create();

    $this->actingAs(User::factory()->create())
        ->delete(route('pets.destroy', $pet))
        ->assertForbidden();

    $this->assertDatabaseHas('pets', ['id' => $pet->id]);
});

test('a user cannot open the edit form of another user pet', function () {
    $pet = Pet::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('pets.edit', $pet))
        ->assertForbidden();
});
