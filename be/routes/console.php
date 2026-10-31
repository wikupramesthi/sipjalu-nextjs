<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Visitor stats aggregation - runs daily at 1:00 AM
Schedule::command('visitor:aggregate')->dailyAt('01:00')->withoutOverlapping();

// Visitor logs pruning - runs weekly on Sunday at 2:00 AM  
Schedule::command('visitor:prune --days=90')->weeklyOn(0, '02:00')->withoutOverlapping();

// Auto-archive old finished aduan - runs daily at 2:30 AM
Schedule::command('aduan:arsipkan')->dailyAt('02:30')->withoutOverlapping();

// Scheduler heartbeat for /health monitoring - runs every minute
Schedule::command('app:heartbeat')->everyMinute()->withoutOverlapping();

// Agenda reminders H-1 - runs daily at 7:00 AM
Schedule::command('agenda:ingatkan')->dailyAt('07:00')->withoutOverlapping();

// Auto-sync popular agendas - runs daily at 3:00 AM
Schedule::command('agenda:populer-otomatis')->dailyAt('03:00')->withoutOverlapping();

// Auto-publish scheduled agendas - runs every 5 minutes
Schedule::command('agenda:publikasikan-terjadwal')->everyFiveMinutes()->withoutOverlapping();

// Auto-archive old finished agendas - runs on the 1st each month at 2:00 AM
Schedule::command('agenda:arsipkan-otomatis')->monthlyOn(1, '02:00')->withoutOverlapping();

// Auto-archive published agendas past their end time - runs every 30 minutes
Schedule::command('agenda:status-terjadwal')->everyThirtyMinutes()->withoutOverlapping();

// Monthly agenda report email - runs on the 1st each month at 8:00 AM
Schedule::command('agenda:laporan-bulanan')->monthlyOn(1, '08:00')->withoutOverlapping();
