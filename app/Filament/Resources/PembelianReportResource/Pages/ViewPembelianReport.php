<?php

namespace App\Filament\Resources\PembelianReportResource\Pages;

use App\Models\Pembelian;
use App\Filament\Resources\PembelianReportResource;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;

class ViewPembelianReport extends Page
{
    protected static string $resource = PembelianReportResource::class;
    protected static string $view = 'filament.resources.pembelian-report-resource.pages.view-pembelian-report';

    public Pembelian $record;

    public function mount(int | string $record): void
    {
        try {
            $this->record = Pembelian::findOrFail($record);
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Data pembelian tidak ditemukan')
                ->danger()
                ->send();
                
            $this->redirect(PembelianReportResource::getUrl());
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->record->pembelianDetails()->with('obat'))
            ->columns([
                TextColumn::make('obat.kode_obat')
                    ->label('Kode Obat')
                    ->searchable(),
                TextColumn::make('obat.nama_obat')
                    ->label('Nama Obat')
                    ->searchable(),
                TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->numeric(),
                TextColumn::make('harga_beli')
                    ->label('Harga Satuan')
                    ->money('IDR'),
                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR'),
            ])
            ->filters([])
            ->actions([])
            ->bulkActions([]);
    }
}
