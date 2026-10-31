<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AduanBaruNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $nomorAduan,
        public string $judulAduan,
        public string $namaPelapor,
        public string $aduanUuid,
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
            'title' => 'Aduan baru masuk',
            'message' => $this->nomorAduan . ' — ' . $this->judulAduan . ' (oleh ' . $this->namaPelapor . ')',
            // Simpan path relatif agar tetap valid saat domain berubah.
            'path' => route('aduans.show', $this->aduanUuid, false),
            'icon' => 'bi-megaphone',
        ];
    }
}
