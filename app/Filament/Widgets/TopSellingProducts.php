<?php

namespace App\Filament\Widgets;

use App\Models\PenjualanDetail;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopSellingProducts extends BaseWidget
{
    protected static ?string $heading = 'Top 5 Obat Terlaris (Bulan Ini)';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PenjualanDetail::query()
                    ->selectRaw('obat_id as id, obat_id, SUM(jumlah) as total_quantity_sold, SUM(subtotal) as total_revenue')
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->groupBy('obat_id')
                    ->orderByRaw('SUM(jumlah) DESC')
                    ->limit(5)
                    ->with('obat')
            )
            ->columns([
                TextColumn::make('rank')
                    ->label('No.')
                    ->rowIndex(),
                TextColumn::make('obat.nama_obat')
                    ->label('Nama Obat'),
                TextColumn::make('total_quantity_sold')
                    ->label('Jumlah Terjual'),
                TextColumn::make('total_revenue')
                    ->label('Total Pendapatan')
                    ->money('IDR'),
            ])
            ->paginated(false);
    }
}
