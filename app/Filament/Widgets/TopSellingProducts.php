<?php

namespace App\Filament\Widgets;

use App\Models\PenjualanDetail;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class TopSellingProducts extends BaseWidget
{
    protected static ?string $heading = 'Top 5 Obat Terlaris (Bulan Ini)';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function getTableRecordKey($record): string
    {
        return (string) $record->obat_id; // Pastikan ini kolom unik
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return PenjualanDetail::query()
                    ->select([
                        'obat_id',
                        DB::raw('SUM(jumlah) as total_quantity_sold'),
                        DB::raw('SUM(subtotal) as total_revenue')
                    ])
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->groupBy('obat_id')
                    ->orderByRaw('SUM(jumlah) DESC')
                    ->with(['obat' => fn($q) => $q->select('id', 'nama_obat')])
                    ->limit(5);
            })
            ->columns([
                TextColumn::make('rank')
                    ->label('No.')
                    ->rowIndex(),

                TextColumn::make('obat.nama_obat')
                    ->label('Nama Obat')
                    ->default(fn($record) => $record->obat?->nama_obat ?? 'Obat Tidak Ditemukan'),

                TextColumn::make('total_quantity_sold')
                    ->label('Jumlah Terjual'),

                TextColumn::make('total_revenue')
                    ->label('Total Pendapatan')
                    ->money('IDR'),
            ])
            ->paginated(false);
    }
}
