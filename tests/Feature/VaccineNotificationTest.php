<?php

use App\Models\Pet;
use App\Models\User;
use App\Models\VaccinationRecord;
use App\Models\Vaccine;
use App\Notifications\VaccineNotification;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\WebPush\WebPushChannel;

test('owners are reminded seven days before a booster is due and on the day itself', function (string $nextDueAt) {
    $this->travelTo('2026-09-20');
    $record = VaccinationRecord::factory()->create(['next_due_at' => $nextDueAt]);
    Notification::fake();

    $this->artisan('app:vaccine-notification')->assertSuccessful();

    Notification::assertSentTo(
        $record->pet->user,
        VaccineNotification::class,
        fn (VaccineNotification $notification) => $notification->record->is($record),
    );
})->with([
    'in 7 days' => '2026-09-27',
    'today' => '2026-09-20',
]);

test('owners are not reminded on the other days', function (string $nextDueAt) {
    $this->travelTo('2026-09-20');
    VaccinationRecord::factory()->create(['next_due_at' => $nextDueAt]);
    Notification::fake();

    $this->artisan('app:vaccine-notification')->assertSuccessful();

    Notification::assertNothingSent();
})->with([
    'yesterday' => '2026-09-19',
    'in 6 days' => '2026-09-26',
    'in 8 days' => '2026-09-28',
]);

test('owners are not reminded of an injection that has since been renewed', function () {
    $this->travelTo('2026-09-20');
    $pet = Pet::factory()->create();
    $vaccine = Vaccine::factory()->create();
    VaccinationRecord::factory()->for($pet)->for($vaccine)->create([
        'administered_at' => '2025-09-27',
        'next_due_at' => '2026-09-27',
    ]);
    VaccinationRecord::factory()->for($pet)->for($vaccine)->create([
        'administered_at' => '2026-09-15',
        'next_due_at' => '2027-09-15',
    ]);
    Notification::fake();

    $this->artisan('app:vaccine-notification')->assertSuccessful();

    Notification::assertNothingSent();
});

test('reminders go by mail unless the owner turned it off, and always by push', function (array $preferences, array $channels) {
    $this->travelTo('2026-09-20');
    $user = User::factory()->create($preferences);
    VaccinationRecord::factory()->for(Pet::factory()->for($user))->create(['next_due_at' => '2026-09-27']);
    Notification::fake();

    $this->artisan('app:vaccine-notification')->assertSuccessful();

    Notification::assertSentTo(
        $user,
        VaccineNotification::class,
        fn (VaccineNotification $notification, array $sentChannels) => $sentChannels === $channels,
    );
})->with([
    'by default' => [[], ['mail', WebPushChannel::class]],
    'mail turned off' => [['mail_notifications' => false], [WebPushChannel::class]],
]);

test('the push reminder names the vaccine and opens the pet page', function () {
    $this->travelTo('2026-09-20');
    $pet = Pet::factory()->create(['name' => 'Filou']);
    $record = VaccinationRecord::factory()->for($pet)->for(Vaccine::factory()->create(['name' => 'Rage']))
        ->create(['next_due_at' => '2026-09-27']);

    $message = (new VaccineNotification($record))->toWebPush($pet->user)->toArray();

    expect($message)->toBe([
        'title' => 'Vaccin à renouveler pour Filou',
        'body' => 'Dans 7 jours : échéance du vaccin Rage. Pensez à prendre rendez-vous chez le vétérinaire.',
        'data' => ['url' => route('pets.show', $pet)],
    ]);
});

test('the mail reminder gives the due date and links to the pet page', function () {
    $this->travelTo('2026-09-20');
    $pet = Pet::factory()->create(['name' => 'Filou']);
    $record = VaccinationRecord::factory()->for($pet)->for(Vaccine::factory()->create(['name' => 'Rage']))
        ->create(['next_due_at' => '2026-09-27']);

    $mail = (new VaccineNotification($record))->toMail($pet->user);

    expect($mail)
        ->subject->toBe('Vaccin à renouveler pour Filou')
        ->introLines->toBe([
            'Le vaccin Rage de Filou arrive à échéance le 27 septembre 2026.',
            "Pensez à prendre rendez-vous chez votre vétérinaire pour faire le rappel, si ce n'est pas déjà fait.",
        ])
        ->actionUrl->toBe(route('pets.show', $pet));
});
