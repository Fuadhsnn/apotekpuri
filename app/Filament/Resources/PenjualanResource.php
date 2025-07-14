<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Filament\Resources\PenjualanResource\RelationManagers;
use App\Models\Penjualan;
use App\Models\Obat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope; // Jika Anda menggunakan Soft Deletes
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Grid;
use Illuminate\Database\Eloquent\Model;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Penjualan';
    protected static ?string $modelLabel = 'Penjualan';
    protected static ?string $pluralModelLabel = 'Penjualan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Ini untuk tampilan detail (ViewAction)
                Forms\Components\Section::make()->schema([
                    Forms\Components\Grid::make(2)->schema([
                        TextInput::make('nomor_nota')->disabled()->label('Nomor Nota'),
                        DatePicker::make('tanggal_penjualan')->disabled()->label('Tanggal Penjualan'),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('user.name')->label('Kasir')->disabled(),
                        TextInput::make('nama_pelanggan')->label('Nama Pelanggan')->disabled(),
                    ]),
                    Grid::make(3)->schema([
                        TextInput::make('total_harga')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->label('Total Harga'),
                        TextInput::make('bayar')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->label('Jumlah Dibayar'),
                        TextInput::make('kembalian')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->label('Kembalian'),
                    ]),
                    TextInput::make('metode_pembayaran')->disabled()->label('Metode Pembayaran'),
                    Forms\Components\Textarea::make('keterangan')->rows(2)->disabled()->label('Keterangan'),

                    // Menampilkan Detail Obat yang Terjual
                    Repeater::make('penjualanDetails') // Nama relasi di model Penjualan
                        ->label('Detail Obat Terjual')
                        ->schema([
                            TextInput::make('obat.nama_obat')->label('Nama Obat')->disabled(), // Relasi obat() di PenjualanDetail
                            TextInput::make('jumlah')->numeric()->disabled(),
                            TextInput::make('harga_jual')
                                ->numeric()
                                ->prefix('Rp')
                                ->disabled(),
                            TextInput::make('subtotal')
                                ->numeric()
                                ->prefix('Rp')
                                ->disabled(),
                            // Tambahkan ini jika Anda punya kolom nomor_resep dan nama_dokter di penjualan_details
                            TextInput::make('nomor_resep')->label('No. Resep')->disabled()->hidden(fn($record) => empty($record->nomor_resep)),
                            TextInput::make('nama_dokter')->label('Nama Dokter')->disabled()->hidden(fn($record) => empty($record->nama_dokter)),
                        ])
                        ->columns(4) // Tampilkan dalam 4 kolom per baris detail
                        ->disabled() // Admin tidak bisa mengedit detail dari sini
                        ->deletable(false) // Tidak bisa dihapus
                        ->addable(false) // Tidak bisa ditambahkan
                        ->collapsible() // Bisa dilipat/dibuka
                        ->defaultItems(0), // Pastikan tidak ada item kosong saat view

                    Placeholder::make('created_at')
                        ->label('Waktu Transaksi')
                        ->content(fn(?Model $record): string => $record ? $record->created_at->format('d M Y, H:i:s') : '-'),
                    Placeholder::make('updated_at')
                        ->label('Terakhir Diperbarui')
                        ->content(fn(?Model $record): string => $record ? $record->updated_at->format('d M Y, H:i:s') : '-'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_nota')
                    ->searchable()
                    ->sortable()
                    ->label('Nomor Nota'),

                Tables\Columns\TextColumn::make('tanggal_penjualan')
                    ->date()
                    ->sortable()
                    ->label('Tanggal Penjualan'),

                // Kolom ini penting: nama kasir
                TextColumn::make('user.name') // Pastikan ada relasi user() di model Penjualan
                    ->label('Kasir')
                    ->searchable(),
                TextColumn::make('nama_pelanggan')
                    ->label('Pelanggan') // Bisa diganti labelnya
                    ->searchable(),
                TextColumn::make('total_harga')
                    ->numeric()
                    ->prefix('Rp'),
                TextColumn::make('bayar')
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Dibayar'),
                TextColumn::make('kembalian')
                    ->numeric()
                    ->prefix('Rp'),
                TextColumn::make('metode_pembayaran')
                    ->label('Metode Bayar') // Label lebih singkat
                    ->searchable(),
            ])
            ->filters([
                Filter::make('tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal'),
                    ])
                    ->label('Metode Pembayaran'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}
