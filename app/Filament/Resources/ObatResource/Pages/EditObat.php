<?php

namespace App\Filament\Resources\ObatResource\Pages;

use App\Filament\Resources\ObatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditObat extends EditRecord
{
    protected static string $resource = ObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->url($this->getResource()::getUrl('index')),
            Actions\DeleteAction::make()
                ->before(function () {
                    // Periksa apakah obat tersebut memiliki catatan terkait
                    $obat = $this->record;

                    $hasPenjualanDetails = $obat->penjualanDetails()->exists();
                    $hasPembelianDetails = $obat->pembelianDetails()->exists();

                    if ($hasPenjualanDetails || $hasPembelianDetails) {
                        $relatedRecords = [];

                        if ($hasPenjualanDetails) {
                            $relatedRecords[] = 'penjualan (penjualan)';
                        }

                        if ($hasPembelianDetails) {
                            $relatedRecords[] = 'pembelian (pembelian)';
                        }

                        $relatedRecordsText = implode(' y ', $relatedRecords);

                        // Tampilkan pemberitahuan dan hentikan tindakan
                        Notification::make()
                            ->danger()
                            ->title('Obatnya tidak bisa dihilangkan')
                            ->body("Obat ini memiliki catatan terkait di {$relatedRecordsText}. Hapus terlebih dahulu rekaman tersebut atau nonaktifkan batasan kunci asing dalam basis data.")
                            ->persistent()
                            ->send();

                        $this->halt();
                    }
                }),
        ];
    }
}
