<?php

namespace App\Console\Commands;

use App\Models\Pet;
use App\Models\VaccinationRecord;
use App\Notifications\VaccineNotification;
use App\Notifications\VaccineOverdueNotification;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

#[Signature('app:vaccine-notification')]
#[Description('Send the vaccine booster reminders due in 7 days and today, and ask a week after the due date whether it was done')]
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
        foreach ($this->petsWithBoosterDueAroundToday() as $pet) {
            foreach ($pet->reminders() as $record) {
                $notification = $this->notificationFor($record);

                if ($notification) {
                    $pet->user->notify($notification);
                }
            }
        }

        return self::SUCCESS;
    }

    /**
     * Pets with a booster due between a week ago and a week from now: the only ones that may get a message today.
     *
     * @return Collection<int, Pet>
     */
    private function petsWithBoosterDueAroundToday(): Collection
    {
        return Pet::query()
            ->whereHas('vaccinationRecords', fn (Builder $query) => $query
                ->whereDate('next_due_at', '>=', today()->subWeek())
                ->whereDate('next_due_at', '<=', today()->addWeek()))
            ->with(['user', 'vaccinationRecords.vaccine'])
            ->get();
    }

    /**
     * The message to send for a booster, from the days left before its due date — none on the other days.
     */
    private function notificationFor(VaccinationRecord $record): ?VaccineNotification
    {
        return match ($record->days_until_due) {
            7, 0 => new VaccineNotification($record),
            -7 => new VaccineOverdueNotification($record),
            default => null,
        };
    }
}
