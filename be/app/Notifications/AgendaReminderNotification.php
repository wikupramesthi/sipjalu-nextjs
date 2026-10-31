<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AgendaReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $agendaTitle,
        public string $agendaUuid,
        public string $waktuLabel,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Pengingat Agenda Besok: ' . $this->agendaTitle)
            ->greeting('Halo, ' . ($notifiable->name ?? ''))
            ->line('Agenda "' . $this->agendaTitle . '" akan dilaksanakan besok (' . $this->waktuLabel . ').')
            ->action('Lihat Detail Agenda', route('agendas.show', $this->agendaUuid));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pengingat: agenda besok',
            'message' => $this->agendaTitle . ' (' . $this->waktuLabel . ')',
            // Simpan path relatif agar tetap valid saat domain berubah.
            'path' => route('agendas.show', $this->agendaUuid, false),
            'icon' => 'bi-alarm',
        ];
    }
}
