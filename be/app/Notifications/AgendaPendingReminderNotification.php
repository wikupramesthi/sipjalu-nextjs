<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AgendaPendingReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $agendaTitle,
        public string $authorName,
        public string $agendaUuid,
        public string $scheduledAt,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Agenda menunggu approval & terjadwal besok',
            'message' => $this->agendaTitle . ' (oleh ' . $this->authorName . ', terbit ' . $this->scheduledAt . ')',
            'path' => route('agendas.edit', $this->agendaUuid, false),
            'icon' => 'bi-exclamation-triangle',
        ];
    }
}
