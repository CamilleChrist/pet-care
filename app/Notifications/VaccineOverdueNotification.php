<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\WebPush\WebPushMessage;

/**
 * Sent a week after the due date when no newer injection has been recorded: asks whether the booster
 * was done and, if so, to add it to the pet page. Same record and channels as VaccineNotification.
 */
class VaccineOverdueNotification extends VaccineNotification
{
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(User $notifiable): MailMessage
    {
        $pet = $this->record->pet;

        return (new MailMessage)
            ->subject("Le rappel de vaccin de {$pet->name} est-il fait ?")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Le vaccin {$this->record->display_name} de {$pet->name} est arrivé à échéance le {$this->record->next_due_at->isoFormat('LL')}.")
            ->line('Si le rappel a été fait, ajoutez-le à sa fiche pour garder son carnet de santé à jour.')
            ->action('Ajouter le rappel', route('pets.vaccination-records.create', $pet))
            ->line('Sinon, pensez à prendre rendez-vous chez votre vétérinaire.')
            ->salutation("L'équipe Pet Care");
    }

    /**
     * Get the push representation of the notification; sw.js opens `data.url` on click.
     */
    public function toWebPush(User $notifiable): WebPushMessage
    {
        $pet = $this->record->pet;

        return (new WebPushMessage)
            ->title("Le rappel de vaccin de {$pet->name} est-il fait ?")
            ->body("Échéance du vaccin {$this->record->display_name} passée le {$this->record->next_due_at->isoFormat('D MMMM')}. Si c'est fait, ajoutez-le à sa fiche ; sinon, pensez à prendre rendez-vous chez le vétérinaire.")
            ->data(['url' => route('pets.vaccination-records.create', $pet)]);
    }
}
