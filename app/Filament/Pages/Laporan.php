<?php

namespace App\Filament\Pages;

use App\Models\Penjualan;
use Filament\Pages\Page;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Contracts\View\View;
use Filament\Tables\Filters\Filter;
use Filament\Pages\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Exports\ExportableTable;


class Laporan extends Page implements \Filament\Tables\Contracts\HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;



    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Laporan Penjualan';
    protected static ?string $title = 'Laporan Penjualan';
    protected static ?string $navigationGroup = 'Laporan'; // Grouping di sidebar

    public ?array $data = [];
    public $startDate = null;
    public $endDate = null;

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Penjualan::query()
                    ->whereBetween('created_at', [
                        $this->startDate,
                        $this->endDate
                    ])
            )
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d-m-Y H:i')
                    ->sortable(),
                TextColumn::make('no_nota')
                    ->label('No. Nota')
                    ->searchable(),
                TextColumn::make('customer_name')
                    ->label('Nama Pelanggan')
                    ->searchable(),
                TextColumn::make('payment_method')
                    ->label('Metode Pembayaran'),
                TextColumn::make('total_harga')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Kasir')
                    ->searchable(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        Grid::make(2)
                            ->schema([
                                DatePicker::make('start_date')
                                    ->label('Dari Tanggal')
                                    ->default(now()->startOfMonth()),
                                DatePicker::make('end_date')
                                    ->label('Sampai Tanggal')
                                    ->default(now()->endOfMonth()),
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['end_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                SelectFilter::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options([
                        'Tunai' => 'Tunai',
                        'QRIS' => 'QRIS',
                    ]),
            ])
            ->actions([
                // Tambahkan action untuk melihat detail jika diperlukan
            ])
            ->bulkActions([
                // Tambahkan bulk actions jika diperlukan
            ])
            ->defaultSort('created_at', 'desc')
            ->enableColumnToggling() // Optional: allow users to show/hide columns
            ->pollingInterval(null); // Disable real-time updates for reports
    }
}
