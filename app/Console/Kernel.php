<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Jalankan Pengecekan Kesehatan (Telat PO & Dead Stock) setiap jam 00:00 malam
        $schedule->command('inventory:daily-check')->dailyAt('00:00');
        
        // Jalankan Pengecekan Stok & Auto-PO setiap jam 22:00 malam
        $schedule->command('inventory:auto-po')->dailyAt('22:00');
        
        // Hapus otomatis pesan chat lebih dari 30 hari (setiap jam 03:00 pagi)
        $schedule->command('chat:clean-old')->dailyAt('03:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
