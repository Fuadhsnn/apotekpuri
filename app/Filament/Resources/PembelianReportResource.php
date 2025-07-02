<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembelianReportResource\Pages;
use App\Models\Pembelian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class PembelianReportResource extends Resource
{
    protected static ?string $model = Pembelian::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporan Pembelian';
    protected static ?string $modelLabel = 'Laporan Pembelian';
    protected static ?string $slug = 'laporan-pembelian';

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
                TextColumn::make('nomor_faktur')
                    ->label('Nomor Faktur')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('supplier.nama_supplier')
                    ->label('Supplier')
                    ->searchable(),
                TextColumn::make('tanggal_pembelian')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('total_harga')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status_pembayaran')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'lunas' => 'success',
                        'belum_lunas' => 'danger',
                    }),
                TextColumn::make('pembelianDetails.jumlah')
                    ->label('Jumlah Item')
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->label('Total Item Dibeli')
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
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal_pembelian', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal_pembelian', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['dari_tanggal'] || !$data['sampai_tanggal']) {
                            return null;
                        }
                        return 'Tanggal: ' . $data['dari_tanggal']->format('d/m/Y') . ' - ' . $data['sampai_tanggal']->format('d/m/Y');
                    }),
                SelectFilter::make('supplier')
                    ->relationship('supplier', 'nama_supplier')
                    ->label('Filter Supplier')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status_pembayaran')
                    ->options([
                        'lunas' => 'Lunas',
                        'belum_lunas' => 'Belum Lunas',
                    ])
                    ->label('Status Pembayaran'),
            ])
            ->actions([])
            ->bulkActions([])
            ->defaultSort('tanggal_pembelian', 'desc');
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
            'index' => Pages\ListPembelianReports::route('/'),
            'detail' => Pages\ViewPembelianReport::route('/{record}/detail'),
        ];
    }
}
