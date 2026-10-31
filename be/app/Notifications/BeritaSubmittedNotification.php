<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BeritaSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $beritaTitle,
        public string $authorName,
        public string $beritaUuid,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Berita baru menunggu persetujuan',
            'message' => $this->beritaTitle . ' (oleh ' . $this->authorName . ')',
            'path' => route('beritas.edit', $this->beritaUuid, false),
            'icon' => 'bi-hourglass-split',
        ];
    }
}
