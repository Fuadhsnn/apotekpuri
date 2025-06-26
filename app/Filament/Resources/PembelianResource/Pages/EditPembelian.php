<?php

namespace App\Filament\Resources\PembelianResource\Pages;

use App\Filament\Resources\PembelianResource;
use App\Models\Obat;
use App\Models\PembelianDetail;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;

class EditPembelian extends EditRecord
{
    protected static string $resource = PembelianResource::class;

    // Menyimpan detail pembelian sebelum diubah
    protected Collection $originalDetails;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Simpan detail pembelian sebelum diubah
        $this->originalDetails = $this->record->pembelianDetails->map(function ($detail) {
            return [
                'id' => $detail->id,
                'obat_id' => $detail->obat_id,
                'jumlah' => $detail->jumlah,
                'harga_beli' => $detail->harga_beli,
            ];
        });

        return $data;
    }

    protected function afterSave(): void
    {
        // Ambil data pembelian yang baru diupdate
        $pembelian = $this->record;
        $newDetails = $pembelian->pembelianDetails;

        // Kumpulkan ID detail yang ada di data baru
        $newDetailIds = $newDetails->pluck('id')->toArray();

        // Proses detail yang dihapus (ada di original tapi tidak ada di new)
        foreach ($this->originalDetails as $originalDetail) {
            if (!in_array($originalDetail['id'], $newDetailIds)) {
                // Detail ini telah dihapus, kembalikan stok
                $obat = Obat::find($originalDetail['obat_id']);
                if ($obat) {
                    $stokBaru = $obat->stok - $originalDetail['jumlah'];
                    $obat->update(['stok' => $stokBaru]);
                }
            }
        }

        // Proses detail yang diubah atau ditambahkan
        foreach ($newDetails as $detail) {
            $obat = Obat::find($detail->obat_id);
            if (!$obat) continue;

            // Cari di original details
            $originalDetail = $this->originalDetails->firstWhere('id', $detail->id);

            if ($originalDetail) {
                // Detail yang diubah
                if ($originalDetail['obat_id'] != $detail->obat_id) {
                    // Obat diganti
                    // Kembalikan stok obat lama
                    $obatLama = Obat::find($originalDetail['obat_id']);
                    if ($obatLama) {
                        $stokBaru = $obatLama->stok - $originalDetail['jumlah'];
                        $obatLama->update(['stok' => $stokBaru]);
                    }

                    // Tambahkan stok obat baru
                    $stokBaru = $obat->stok + $detail->jumlah;
                    $obat->update(['stok' => $stokBaru]);
                } else {
                    // Obat sama, hitung selisih jumlah
                    $selisih = $detail->jumlah - $originalDetail['jumlah'];
                    if ($selisih != 0) {
                        $stokBaru = $obat->stok + $selisih;
                        $obat->update(['stok' => $stokBaru]);
                    }
                }

                // Update harga beli jika berubah
                if ($obat->harga_beli != $detail->harga_beli) {
                    $obat->update(['harga_beli' => $detail->harga_beli]);
                }
            } else {
                // Detail baru ditambahkan
                $stokBaru = $obat->stok + $detail->jumlah;
                $obat->update([
                    'stok' => $stokBaru,
                    'harga_beli' => $detail->harga_beli
                ]);
            }
        }

        // Tampilkan notifikasi sukses
        Notification::make()
            ->title('Stok obat berhasil diperbarui')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [

            // Tombol Back/Kembali
            Actions\Action::make('back')
                ->label('Kembali')
                ->url($this->getResource()::getUrl('index')),

            Actions\DeleteAction::make()
                ->before(function () {
                    // Sebelum menghapus pembelian, kembalikan stok obat
                    $pembelian = $this->record;

                    foreach ($pembelian->pembelianDetails as $detail) {
                        $obat = Obat::find($detail->obat_id);
                        if ($obat) {
                            // Kurangi stok obat sesuai dengan jumlah yang dibeli
                            $stokBaru = $obat->stok - $detail->jumlah;
                            $obat->update(['stok' => $stokBaru]);
                        }
                    }

                    Notification::make()
                        ->title('Stok obat berhasil dikembalikan')
                        ->success()
                        ->send();
                }),
        ];
    }
}
