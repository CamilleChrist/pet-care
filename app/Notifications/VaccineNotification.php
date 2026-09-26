<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\VaccinationRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class VaccineNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public VaccinationRecord $record) {}

    /**
     * Get the notification's delivery channels.
     *
     * Push is skipped by the channel itself when the user has no subscribed device.
     *
     * @return array<int, string>
     */
    public function via(User $notifiable): array
    {
        return ['mail', WebPushChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(User $notifiable): MailMessage
    {
        $pet = $this->record->pet;

        return (new MailMessage)
            ->subject("Vaccin à renouveler pour {$pet->name}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Le vaccin {$this->record->display_name} de {$pet->name} arrive à échéance le {$this->record->next_due_at->isoFormat('LL')}.")
            ->line("Pensez à prendre rendez-vous chez votre vétérinaire pour faire le rappel, si ce n'est pas déjà fait.")
            ->action('Voir la fiche', route('pets.show', $pet))
            ->salutation("L'équipe Pet Care");
    }

    /**
     * Get the push representation of the notification; sw.js opens `data.url` on click.
     */
    public function toWebPush(User $notifiable): WebPushMessage
    {
        $pet = $this->record->pet;

        return (new WebPushMessage)
            ->title("Vaccin à renouveler pour {$pet->name}")
            ->body("{$this->record->status_label} : échéance du vaccin {$this->record->display_name}. Pensez à prendre rendez-vous chez le vétérinaire.")
            ->data(['url' => route('pets.show', $pet)]);
    }
}
