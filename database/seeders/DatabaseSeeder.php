<?php

namespace Database\Seeders;

use App\Enums\PetGender;
use App\Models\Breed;
use App\Models\Pet;
use App\Models\User;
use App\Models\VaccinationRecord;
use App\Models\Vaccine;
use App\Models\WeightRecord;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BreedSeeder::class,
            VaccineSeeder::class,
        ]);

        User::factory()->admin()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->dog($user);
        $this->cat($user);

        User::factory(4)
            ->has(Pet::factory(2)->recycle(Breed::all())->has(WeightRecord::factory(3)))
            ->create();
    }

    /** Un chien adulte : poids stabilisé, un rappel dépassé et un rappel proche. */
    private function dog(User $user): void
    {
        $pet = Pet::factory()
            ->for($user)
            ->for(Breed::firstWhere('name', 'Labrador Retriever'))
            ->create([
                'name' => 'Nala',
                'gender' => PetGender::Female,
                'birth_date' => now()->subYears(3)->subMonths(2),
                'last_vet_visit_at' => now()->subMonths(2),
                'health_notes' => 'Allergie au poulet. Surveiller le poids, tendance à l\'embonpoint.',
            ]);

        WeightRecord::factory()->for($pet)->createMany([
            ['weight' => 12.4, 'recorded_at' => now()->subMonths(15)],
            ['weight' => 18.9, 'recorded_at' => now()->subMonths(12)],
            ['weight' => 24.2, 'recorded_at' => now()->subMonths(9)],
            ['weight' => 27.5, 'recorded_at' => now()->subMonths(6)],
            ['weight' => 29.1, 'recorded_at' => now()->subMonths(3)],
            ['weight' => 28.4, 'recorded_at' => now()->subWeeks(2)],
        ]);

        $vaccines = Vaccine::where('species', 'dog')->pluck('id', 'name');

        VaccinationRecord::factory()->for($pet)->createMany([
            [
                'vaccine_id' => $vaccines['Maladie de Carré'],
                'administered_at' => now()->subMonths(13),
                'next_due_at' => now()->subMonths(1),
            ],
            [
                'vaccine_id' => $vaccines['Leptospirose'],
                'administered_at' => now()->subMonths(11),
                'next_due_at' => now()->addWeeks(3),
            ],
            [
                'vaccine_id' => $vaccines['Rage'],
                'administered_at' => now()->subMonths(5),
                'next_due_at' => now()->addMonths(7),
            ],
            [
                'vaccine_id' => $vaccines['Rage'],
                'administered_at' => now()->subMonths(29),
                'next_due_at' => now()->subMonths(5),
            ],
        ]);
    }

    /** Un chaton : poids en pleine croissance, primo-vaccination à jour. */
    private function cat(User $user): void
    {
        $pet = Pet::factory()
            ->for($user)
            ->for(Breed::firstWhere('name', 'Chartreux'))
            ->create([
                'name' => 'Pilou',
                'gender' => PetGender::Male,
                'birth_date' => now()->subMonths(8),
                'last_vet_visit_at' => now()->subWeeks(5),
                'health_notes' => null,
            ]);

        WeightRecord::factory()->for($pet)->createMany([
            ['weight' => 1.1, 'recorded_at' => now()->subMonths(5)],
            ['weight' => 2.0, 'recorded_at' => now()->subMonths(3)],
            ['weight' => 3.2, 'recorded_at' => now()->subMonths(1)],
            ['weight' => 3.6, 'recorded_at' => now()->subWeeks(1)],
        ]);

        $vaccines = Vaccine::where('species', 'cat')->pluck('id', 'name');

        VaccinationRecord::factory()->for($pet)->createMany([
            [
                'vaccine_id' => $vaccines['Typhus'],
                'administered_at' => now()->subMonths(5),
                'next_due_at' => now()->addMonths(7),
            ],
            [
                'vaccine_id' => $vaccines['Leucose féline'],
                'administered_at' => now()->subWeeks(5),
                'next_due_at' => now()->addMonths(11),
            ],
        ]);
    }
}
