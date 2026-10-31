<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class KontakBaruNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $nama,
        public string $cuplikan,
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
            'title' => 'Pesan kontak baru',
            'message' => 'Dari ' . $this->nama . ': ' . $this->cuplikan,
            // Simpan path relatif agar tetap valid saat domain berubah.
            'path' => route('layanan.kontak', [], false),
            'icon' => 'bi-envelope',
        ];
    }
}
