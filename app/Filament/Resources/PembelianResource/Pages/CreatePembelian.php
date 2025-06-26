<?php

namespace App\Filament\Resources\PembelianResource\Pages;

use App\Filament\Resources\PembelianResource;
use App\Models\Obat;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreatePembelian extends CreateRecord
{
    protected static string $resource = PembelianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tombol Back/Kembali
            Actions\Action::make('back')
                ->label('Kembali')
                ->url($this->getResource()::getUrl('index')),
        ];
    }

    protected function afterCreate(): void
    {
        // Ambil data pembelian yang baru dibuat
        $pembelian = $this->record;

        // Perbarui stok obat untuk setiap item yang dibeli
        foreach ($pembelian->pembelianDetails as $detail) {
            $obat = Obat::find($detail->obat_id);

            if ($obat) {
                // Tambahkan stok obat sesuai dengan jumlah yang dibeli
                $stokLama = $obat->stok;
                $stokBaru = $stokLama + $detail->jumlah;

                $obat->update([
                    'stok' => $stokBaru
                ]);

                // Jika harga beli berubah, perbarui harga beli di data obat
                if ($obat->harga_beli != $detail->harga_beli) {
                    $obat->update([
                        'harga_beli' => $detail->harga_beli
                    ]);
                }
            }
        }

        // Tampilkan notifikasi sukses
        Notification::make()
            ->title('Stok obat berhasil diperbarui')
            ->success()
            ->send();
    }
}
