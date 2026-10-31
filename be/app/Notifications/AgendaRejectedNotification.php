<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AgendaRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $agendaTitle,
        public string $agendaUuid,
        public ?string $alasan = null,
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
            'title' => 'Agenda dikembalikan sebagai draft',
            'message' => $this->agendaTitle . ($this->alasan ? ' — Alasan: ' . $this->alasan : ' — silakan perbaiki lalu kirim ulang.'),
            // Simpan path relatif agar tetap valid saat domain berubah.
            'path' => route('agendas.edit', $this->agendaUuid, false),
            'icon' => 'bi-arrow-counterclockwise',
        ];
    }
}
