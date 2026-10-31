<?php

namespace App\Console\Commands;

use App\Models\Agenda;
use App\Notifications\AgendaReminderNotification;
use App\Services\WhatsappGateway;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class KirimPengingatAgenda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agenda:ingatkan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim pengingat H-1 untuk agenda published yang mulai besok';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $besok = now()->copy()->addDay();

        $agendas = Agenda::with('user')
            ->where('status', 'published')
            ->whereDate('waktu_mulai', $besok->toDateString())
            ->get();

        $terkirim = 0;

        foreach ($agendas as $agenda) {
            try {
                $author = $agenda->user;
                $waktu = $agenda->waktu_mulai?->translatedFormat('d M Y, H:i') ?? '-';

                if ($author) {
                    $author->notify(new AgendaReminderNotification(
                        $agenda->title,
                        $agenda->uuid,
                        $waktu,
                    ));

                    WhatsappGateway::send($author->no_hp ?? null,
                        'Pengingat: "' . $agenda->title . '" mulai besok (' . $waktu . '). Detail: ' . route('agendas.show', $agenda->uuid));
                }

                $agenda->logs()->create([
                    'user_uuid' => null,
                    'aksi'      => 'pengingat_terkirim',
                    'catatan'   => 'Otomatis H-1',
                ]);

                $terkirim++;
            } catch (\Throwable $e) {
                Log::warning('Gagal kirim pengingat agenda ' . $agenda->uuid . ': ' . $e->getMessage());
            }
        }

        $this->info("Pengingat terkirim untuk {$terkirim} agenda.");

        // Ingatkan admin bila ada agenda pending yang terjadwal terbit besok
        $pending = Agenda::with('user')
            ->where('status', 'pending')
            ->whereDate('scheduled_at', $besok->toDateString())
            ->get();

        if ($pending->isNotEmpty()) {
            $admins = \App\Models\User::role(['super-admin', 'admin'])->get();
            foreach ($pending as $agenda) {
                foreach ($admins as $admin) {
                    $admin->notify(new \App\Notifications\AgendaPendingReminderNotification(
                        $agenda->title,
                        $agenda->user?->name ?? 'OPD',
                        $agenda->uuid,
                        $agenda->scheduled_at?->format('d M Y H:i') ?? '-',
                    ));
                }
            }
            $this->info($pending->count() . ' agenda pending terjadwal besok diingatkan ke admin.');
        }

        return self::SUCCESS;
    }
}
