<?php

namespace Database\Factories;

use App\Enums\PetGender;
use App\Models\Breed;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'breed_id' => Breed::factory(),
            'name' => fake()->firstName(),
            'gender' => fake()->randomElement(PetGender::cases()),
            'birth_date' => fake()->date(),
        ];
    }
}
