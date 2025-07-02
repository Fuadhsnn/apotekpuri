<?php

namespace App\Filament\Resources\PenjualanReportResource\Pages;

use App\Models\Penjualan;
use App\Filament\Resources\PenjualanReportResource;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;

class ViewPenjualanReport extends Page
{
    protected static string $resource = PenjualanReportResource::class;
    protected static string $view = 'view-penjualan-report';

    public Penjualan $record;

    public function mount(int | string $record): void
    {
        try {
            $this->record = Penjualan::findOrFail($record);
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Data penjualan tidak ditemukan')
                ->danger()
                ->send();
                
            $this->redirect(PenjualanReportResource::getUrl());
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->record->penjualanDetails()->with('obat'))
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
                TextColumn::make('harga_jual')
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
