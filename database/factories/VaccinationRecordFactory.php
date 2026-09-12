<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\VaccinationRecord;
use App\Models\Vaccine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VaccinationRecord>
 */
class VaccinationRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pet_id' => Pet::factory(),
            'vaccine_id' => Vaccine::factory(),
            'administered_at' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'veterinarian_name' => fake()->name(),
            'clinic_name' => fake()->company(),
            'lot_number' => fake()->bothify('??####'),
        ];
    }
}
