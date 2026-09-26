<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\VaccinationRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

/**
 * The owner's daily digest: every booster to remind today, all pets together, in one mail and one push.
 */
class VaccineNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param  Collection<int, VaccinationRecord>  $records
     */
    public function __construct(public Collection $records) {}

    /**
     * Get the notification's delivery channels.
     *
     * Mail follows the profile preference; push is skipped by the channel itself when the user has no subscribed device.
     *
     * @return array<int, string>
     */
    public function via(User $notifiable): array
    {
        return $notifiable->mail_notifications ? ['mail', WebPushChannel::class] : [WebPushChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(User $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->title())
            ->greeting("Bonjour {$notifiable->name},");

        foreach ($this->byDueDate() as $record) {
            $mail->line("- {$this->summary($record)}, échéance le {$record->next_due_at->isoFormat('LL')}");
        }

        return $mail
            ->line('Pensez à prendre rendez-vous chez votre vétérinaire pour les rappels à faire.')
            ->line("Un rappel déjà fait ? Ajoutez-le à la fiche de l'animal pour garder son carnet de santé à jour.")
            ->action('Voir mes rappels', route('dashboard'))
            ->salutation("L'équipe Pet Care");
    }

    /**
     * Get the push representation of the notification; sw.js opens `data.url` on click.
     */
    public function toWebPush(User $notifiable): WebPushMessage
    {
        $lines = $this->byDueDate()->map(fn (VaccinationRecord $record) => $this->summary($record));

        return (new WebPushMessage)
            ->title($this->title())
            ->body($lines->push("Pensez à prendre rendez-vous chez le vétérinaire, ou ajoutez le rappel à la fiche s'il est déjà fait.")->implode("\n"))
            ->data(['url' => route('dashboard')]);
    }

    /**
     * « Rappel de vaccin pour Filou », « Rappels de vaccin pour Moka et Filou ».
     */
    private function title(): string
    {
        $pets = $this->byDueDate()->map(fn (VaccinationRecord $record) => $record->pet->name)->unique()->join(', ', ' et ');

        return trans_choice('{1} Rappel de vaccin pour :pets|[2,*] Rappels de vaccin pour :pets', $this->records->count(), ['pets' => $pets]);
    }

    /**
     * « Dans 7 jours : Rage de Filou », « Aujourd'hui : … », « En retard : … ».
     */
    private function summary(VaccinationRecord $record): string
    {
        return "{$record->status_label} : {$record->display_name} de {$record->pet->name}";
    }

    /**
     * The late ones first. Sorted here rather than by the command: a queued collection comes back in id order.
     *
     * @return Collection<int, VaccinationRecord>
     */
    private function byDueDate(): Collection
    {
        return $this->records->sortBy('next_due_at');
    }
}
