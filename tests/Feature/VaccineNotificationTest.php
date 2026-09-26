<?php

use App\Models\Pet;
use App\Models\User;
use App\Models\VaccinationRecord;
use App\Models\Vaccine;
use App\Notifications\VaccineNotification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\WebPush\WebPushChannel;

test('owners are reminded seven days before a booster is due, on the day, and a week after', function (string $nextDueAt) {
    $this->travelTo('2026-09-20');
    $record = VaccinationRecord::factory()->create(['next_due_at' => $nextDueAt]);
    Notification::fake();

    $this->artisan('app:vaccine-notification')->assertSuccessful();

    Notification::assertSentTo(
        $record->pet->user,
        VaccineNotification::class,
        fn (VaccineNotification $notification) => $notification->records->modelKeys() === [$record->id],
    );
})->with([
    'in 7 days' => '2026-09-27',
    'today' => '2026-09-20',
    '7 days ago' => '2026-09-13',
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
    '6 days ago' => '2026-09-14',
    '8 days ago' => '2026-09-12',
]);

test('owners get a single message for all their pets boosters of the day', function () {
    $this->travelTo('2026-09-20');
    $user = User::factory()->create();
    $dueSoon = VaccinationRecord::factory()->for(Pet::factory()->for($user))->create(['next_due_at' => '2026-09-27']);
    $late = VaccinationRecord::factory()->for(Pet::factory()->for($user))->create(['next_due_at' => '2026-09-13']);
    VaccinationRecord::factory()->create(['next_due_at' => '2026-09-20']);
    Notification::fake();

    $this->artisan('app:vaccine-notification')->assertSuccessful();

    Notification::assertSentTo(
        $user,
        VaccineNotification::class,
        fn (VaccineNotification $notification) => $notification->records->sortBy('id')->modelKeys() === [$dueSoon->id, $late->id],
    );
});

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

test('owners are not asked about a booster they already recorded', function () {
    $this->travelTo('2026-09-20');
    $pet = Pet::factory()->create();
    $vaccine = Vaccine::factory()->create();
    VaccinationRecord::factory()->for($pet)->for($vaccine)->create([
        'administered_at' => '2025-09-13',
        'next_due_at' => '2026-09-13',
    ]);
    VaccinationRecord::factory()->for($pet)->for($vaccine)->create([
        'administered_at' => '2026-09-16',
        'next_due_at' => '2027-09-16',
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

test('the push lists the boosters of the day, the late ones first, and opens the dashboard', function () {
    $this->travelTo('2026-09-20');
    $user = User::factory()->create();
    $rage = VaccinationRecord::factory()
        ->for(Pet::factory()->for($user)->create(['name' => 'Filou']))
        ->for(Vaccine::factory()->create(['name' => 'Rage']))
        ->create(['next_due_at' => '2026-09-27']);
    $leptospirose = VaccinationRecord::factory()
        ->for(Pet::factory()->for($user)->create(['name' => 'Moka']))
        ->for(Vaccine::factory()->create(['name' => 'Leptospirose']))
        ->create(['next_due_at' => '2026-09-13']);

    $message = (new VaccineNotification(new Collection([$rage, $leptospirose])))->toWebPush($user)->toArray();

    expect($message)->toBe([
        'title' => 'Rappels de vaccin pour Moka et Filou',
        'body' => "En retard : Leptospirose de Moka\nDans 7 jours : Rage de Filou\nPensez à prendre rendez-vous chez le vétérinaire, ou ajoutez le rappel à la fiche s'il est déjà fait.",
        'data' => ['url' => route('dashboard')],
    ]);
});

test('the mail lists the boosters of the day with their due dates and asks to record the ones done', function () {
    $this->travelTo('2026-09-20');
    $user = User::factory()->create();
    $rage = VaccinationRecord::factory()
        ->for(Pet::factory()->for($user)->create(['name' => 'Filou']))
        ->for(Vaccine::factory()->create(['name' => 'Rage']))
        ->create(['next_due_at' => '2026-09-27']);
    $leptospirose = VaccinationRecord::factory()
        ->for(Pet::factory()->for($user)->create(['name' => 'Moka']))
        ->for(Vaccine::factory()->create(['name' => 'Leptospirose']))
        ->create(['next_due_at' => '2026-09-13']);

    $mail = (new VaccineNotification(new Collection([$rage, $leptospirose])))->toMail($user);

    expect($mail)
        ->subject->toBe('Rappels de vaccin pour Moka et Filou')
        ->introLines->toBe([
            '- En retard : Leptospirose de Moka, échéance le 13 septembre 2026',
            '- Dans 7 jours : Rage de Filou, échéance le 27 septembre 2026',
            'Pensez à prendre rendez-vous chez votre vétérinaire pour les rappels à faire.',
            "Un rappel déjà fait ? Ajoutez-le à la fiche de l'animal pour garder son carnet de santé à jour.",
        ])
        ->actionText->toBe('Voir mes rappels')
        ->actionUrl->toBe(route('dashboard'));
});

test('the title is singular for a single booster', function () {
    $this->travelTo('2026-09-20');
    $pet = Pet::factory()->create(['name' => 'Filou']);
    $record = VaccinationRecord::factory()->for($pet)->create(['next_due_at' => '2026-09-20']);

    $mail = (new VaccineNotification(new Collection([$record])))->toMail($pet->user);

    expect($mail->subject)->toBe('Rappel de vaccin pour Filou');
});
