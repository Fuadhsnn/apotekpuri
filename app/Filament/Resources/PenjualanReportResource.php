<?php

namespace App\Filament\Resources;

use App\Models\Penjualan;
use App\Models\Obat;
use App\Models\PenjualanDetail;
use App\Filament\Resources\PenjualanReportResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ExportAction;

class PenjualanReportResource extends Resource
{
    protected static ?string $model = Penjualan::class;
    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporan Penjualan';
    protected static ?string $modelLabel = 'Laporan Penjualan';
    protected static ?string $slug = 'laporan-penjualan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form fields if needed
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_nota')
                    ->label('Nomor Nota')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal_penjualan')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('nama_pelanggan')
                    ->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('total_harga')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('penjualanDetails.jumlah')
                    ->label('Jumlah Item')
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->label('Total Item Terjual')
                    ]),
            ])
            ->filters([
                Filter::make('tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal_penjualan', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal_penjualan', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['dari_tanggal'] || !$data['sampai_tanggal']) {
                            return null;
                        }
                        return 'Tanggal: ' . $data['dari_tanggal']->format('d/m/Y') . ' - ' . $data['sampai_tanggal']->format('d/m/Y');
                    }),
                SelectFilter::make('obat_id')
                    ->label('Obat')
                    ->options(fn() => Obat::pluck('nama_obat', 'id')->toArray())
                    ->query(function (Builder $query, $data) {
                        if ($data['value']) {
                            $query->whereHas('penjualanDetails', function ($q) use ($data) {
                                $q->where('obat_id', $data['value']);
                            });
                        }
                    })
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\Action::make('detail')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->url(fn(Penjualan $record): string => PenjualanReportResource::getUrl('detail', ['record' => $record])),
            ])
            ->bulkActions([])
            ->defaultSort('tanggal_penjualan', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // Relations if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenjualanReports::route('/'),
            'detail' => Pages\ViewPenjualanReport::route('/{record}/detail'),
        ];
    }
}
