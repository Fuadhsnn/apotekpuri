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
        // $schedule->command('inspire')->hourly();
        $schedule->command('app:check-low-stock')->daily()->at('08:00');
        
        // Jadwalkan impor obat dari SatuSehat setiap hari pada pukul 01:00 pagi
        // Uncomment baris di bawah ini untuk mengaktifkan penjadwalan otomatis
        // $schedule->command('satusehat:import-medications 100')->dailyAt('01:00');
        
        // Atau jadwalkan impor obat dari SatuSehat setiap minggu pada hari Senin pukul 01:00 pagi
        // $schedule->command('satusehat:import-medications 100')->weeklyOn(1, '01:00');
        
        // Atau jadwalkan impor obat dari SatuSehat setiap bulan pada tanggal 1 pukul 01:00 pagi
        // $schedule->command('satusehat:import-medications 100')->monthlyOn(1, '01:00');
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
