<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AgendaApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $agendaTitle,
        public string $agendaUuid,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Agenda disetujui & dipublikasikan',
            'message' => $this->agendaTitle,
            // Simpan path relatif agar tetap valid saat domain berubah.
            'path' => route('agendas.edit', $this->agendaUuid, false),
            'icon' => 'bi-check-circle',
        ];
    }
}
