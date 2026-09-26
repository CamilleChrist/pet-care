<?php

namespace App\Console\Commands;

use App\Models\Pet;
use App\Models\VaccinationRecord;
use App\Notifications\VaccineNotification;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:vaccine-notification')]
#[Description('Send the vaccine booster reminders due in 7 days and today')]
class SendVaccineNotification extends Command
{
    /**
     * Execute the console command.
     *
     * Goes through Pet::reminders() (the latest injection of each vaccine), so an injection that has
     * since been renewed no longer triggers a reminder even though it keeps its next_due_at.
     */
    public function handle(): int
    {
        Pet::query()
            ->whereHas('vaccinationRecords', fn ($query) => $query
                ->whereDate('next_due_at', '>=', today())
                ->whereDate('next_due_at', '<=', today()->addWeek()))
            ->with(['user', 'vaccinationRecords.vaccine'])
            ->each(fn (Pet $pet) => $pet->reminders()
                ->filter(fn (VaccinationRecord $record) => in_array($record->days_until_due, [0, 7], true))
                ->each(fn (VaccinationRecord $record) => $pet->user->notify(new VaccineNotification($record))));

        return self::SUCCESS;
    }
}
