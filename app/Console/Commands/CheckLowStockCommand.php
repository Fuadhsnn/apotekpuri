<?php

namespace App\Console\Commands;

use App\Models\Obat;
use App\Models\StockNotification;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Illuminate\Console\Command;

class CheckLowStockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-low-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memeriksa stok obat yang menipis dan mengirimkan notifikasi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threshold = 10; // Batas stok menipis
        
        $lowStockObats = Obat::where('stok', '<=', $threshold)->get();
        
        $count = $lowStockObats->count();
        
        if ($count > 0) {
            $this->info("Ditemukan {$count} obat dengan stok menipis.");
            
            // Kirim notifikasi untuk setiap obat dengan stok menipis
            foreach ($lowStockObats as $obat) {
                $this->info("- {$obat->nama_obat}: {$obat->stok} tersisa");
                
                // Simpan notifikasi ke database jika belum ada
                $notification = StockNotification::firstOrCreate(
                    ['obat_id' => $obat->id, 'is_read' => false],
                    ['current_stock' => $obat->stok]
                );
                
                // Update stok saat ini jika sudah ada notifikasi
                if (!$notification->wasRecentlyCreated && $notification->current_stock != $obat->stok) {
                    $notification->update(['current_stock' => $obat->stok]);
                }
                
                // Buat notifikasi database yang akan ditampilkan di panel admin
                Notification::make()
                    ->warning()
                    ->title("Stok Obat Menipis")
                    ->body("Stok {$obat->nama_obat} tinggal {$obat->stok}. Segera lakukan pembelian.")
                    ->actions([
                        Action::make('view')
                            ->button()
                            ->url(route('filament.admin.resources.obats.edit', $obat))
                            ->label('Lihat Detail'),
                    ])
                    ->persistent()
                    ->send();
            }
        } else {
            $this->info("Semua obat memiliki stok yang cukup.");
            
            // Hapus semua notifikasi yang sudah tidak relevan
            StockNotification::whereNotIn('obat_id', $lowStockObats->pluck('id'))->delete();
        }
        
        return Command::SUCCESS;
    }
}