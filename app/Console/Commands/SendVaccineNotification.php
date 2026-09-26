<?php

namespace App\Console\Commands;

use App\Models\VaccinationRecord;
use App\Notifications\VaccineNotification;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

#[Signature('app:vaccine-notification')]
#[Description('Send each owner one digest of the boosters due in 7 days, today, or 7 days ago')]
class SendVaccineNotification extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        foreach ($this->notificationsToRemindToday()->groupBy('pet.user_id') as $notifications) {
            $owner = $notifications->first()->pet->user;

            $owner->notify(new VaccineNotification($notifications));
        }

        return self::SUCCESS;
    }

    /**
     * The injections due in 7 days, today, or 7 days ago (then the message asks whether it was done).
     *
     * Only the latest injection of each vaccine counts (Pet::reminders()): an injection that has since
     * been renewed keeps its next_due_at, and must not trigger a reminder for a vaccine already done.
     *
     * @return Collection<int, VaccinationRecord>
     */
    private function notificationsToRemindToday(): Collection
    {
        return VaccinationRecord::query()
            ->where(fn (Builder $query) => $query
                ->whereDate('next_due_at', today()->addWeek())
                ->orWhereDate('next_due_at', today())
                ->orWhereDate('next_due_at', today()->subWeek()))
            ->with(['vaccine', 'pet.user', 'pet.vaccinationRecords.vaccine'])
            ->get()
            ->filter(fn (VaccinationRecord $record) => $record->pet->reminders()->contains($record));
    }
}
