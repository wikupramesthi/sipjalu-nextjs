<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AgendaMonthlyReportNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $periodLabel,
        public array $stats,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $stats = $this->stats;

        $mail = (new MailMessage)
            ->subject('Laporan Agenda Bulanan — ' . $this->periodLabel)
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Berikut ringkasan aktivitas agenda untuk periode ' . $this->periodLabel . '.')
            ->line('Agenda dibuat: **' . number_format($stats['created']) . '**')
            ->line('Menunggu persetujuan: **' . number_format($stats['pending']) . '**')
            ->line('Draf: **' . number_format($stats['draft']) . '**')
            ->line('Dijadwalkan terbit: **' . number_format($stats['scheduled']) . '**')
            ->line('Terbit (approved): **' . number_format($stats['published']) . '**')
            ->line('Total agenda aktif published: **' . number_format($stats['publishedTotal']) . '**');

        if (! empty($stats['topOpd'])) {
            $mail->line('OPD teraktif: ' . $stats['topOpd']);
        }

        return $mail
            ->action('Buka Laporan', route('agendas.laporan'))
            ->line('Email ini dikirim otomatis oleh sistem Agenda Kota Bekasi.');
    }
}
