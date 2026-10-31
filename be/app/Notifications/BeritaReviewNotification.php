<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BeritaReviewNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $beritaTitle,
        public string $beritaUuid,
        public string $reviewAction,
        public ?string $note = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        [$title, $icon] = match ($this->reviewAction) {
            'approved' => ['Berita disetujui dan dipublikasikan', 'bi-check-circle'],
            'revision' => ['Revisi berita diminta', 'bi-pencil-square'],
            default => ['Berita dikembalikan sebagai draft', 'bi-arrow-counterclockwise'],
        };

        return [
            'title' => $title,
            'message' => $this->beritaTitle . ($this->note ? ' — ' . $this->note : ''),
            'path' => route('beritas.edit', $this->beritaUuid, false),
            'icon' => $icon,
        ];
    }
}
