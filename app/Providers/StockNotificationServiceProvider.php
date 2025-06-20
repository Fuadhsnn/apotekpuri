<?php

namespace App\Providers;

use App\Models\Obat;
use App\Models\StockNotification;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Illuminate\Support\ServiceProvider;

class StockNotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Periksa stok obat yang menipis saat aplikasi boot
        // Ini akan berjalan setiap kali aplikasi dimuat
        $this->checkLowStock();
    }
    
    /**
     * Periksa stok obat yang menipis dan kirim notifikasi
     */
    protected function checkLowStock(): void
    {
        // Hanya jalankan di lingkungan web (bukan saat menjalankan artisan command)
        if (app()->runningInConsole()) {
            return;
        }
        
        $threshold = 10; // Batas stok menipis
        
        // Ambil obat dengan stok menipis (kurang dari atau sama dengan threshold)
        $lowStockObats = Obat::where('stok', '<=', $threshold)->get();
        
        // Jika ada obat dengan stok menipis, kirim notifikasi
        if ($lowStockObats->count() > 0) {
            // Buat satu notifikasi ringkasan untuk semua obat dengan stok menipis
            $obatList = $lowStockObats->map(function ($obat) {
                // Simpan notifikasi ke database jika belum ada
                $notification = StockNotification::firstOrCreate(
                    ['obat_id' => $obat->id, 'is_read' => false],
                    ['current_stock' => $obat->stok]
                );
                
                // Update stok saat ini jika sudah ada notifikasi
                if (!$notification->wasRecentlyCreated && $notification->current_stock != $obat->stok) {
                    $notification->update(['current_stock' => $obat->stok]);
                }
                
                return "- {$obat->nama_obat}: {$obat->stok} tersisa";
            })->join("\n");
            
            Notification::make('low-stock-summary')
                ->warning()
                ->title("Stok Obat Menipis")
                ->body("Beberapa obat memiliki stok yang menipis dan perlu diisi ulang:\n{$obatList}")
                ->actions([
                    Action::make('view')
                        ->button()
                        ->url(url('/admin/obats'))
                        ->label('Lihat Semua Obat'),
                ])
                ->persistent()
                ->send();
        }
    }
}