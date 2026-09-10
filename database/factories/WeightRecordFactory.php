<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\WeightRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WeightRecord>
 */
class WeightRecordFactory extends Factory
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
            'weight' => fake()->randomFloat(2, 1, 100),
            'recorded_at' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
        ];
    }
}
