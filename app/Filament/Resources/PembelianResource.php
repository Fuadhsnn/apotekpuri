<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembelianResource\Pages;
use App\Models\Pembelian;
use App\Models\Obat;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PembelianResource extends Resource
{
    protected static ?string $model = Pembelian::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Pembelian';
    protected static ?string $modelLabel = 'Pembelian';
    protected static ?string $pluralModelLabel = 'Pembelian';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nomor_faktur')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->disabled()
                    ->dehydrated()
                    ->default(function () {
                        // Mendapatkan tanggal saat ini
                        $tanggal = now()->format('Ymd');

                        // Mendapatkan nomor urut terakhir
                        $lastPembelian = Pembelian::whereDate('created_at', now())
                            ->orderBy('nomor_faktur', 'desc')
                            ->first();

                        $nomorUrut = '001';

                        if ($lastPembelian) {
                            $lastNomor = substr($lastPembelian->nomor_faktur, -3);
                            $nomorUrut = str_pad((int)$lastNomor + 1, 3, '0', STR_PAD_LEFT);
                        }

                        return "PB/{$tanggal}/{$nomorUrut}";
                    })
                    ->label('Nomor Faktur')
                    ->placeholder('Nomor faktur akan dibuat otomatis')
                    ->helperText('Format: PB/YYYYMMDD/XXX'),

                Forms\Components\Select::make('supplier_id')
                    ->relationship('supplier', 'nama_supplier')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Supplier'),

                Forms\Components\DatePicker::make('tanggal_pembelian')
                    ->required()
                    ->label('Tanggal Pembelian'),

                Forms\Components\Select::make('status_pembayaran')
                    ->options([
                        'lunas' => 'Lunas',
                        'belum_lunas' => 'Belum Lunas'
                    ])
                    ->required()
                    ->label('Status Pembayaran'),

                Forms\Components\Repeater::make('pembelianDetails')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('obat_id')
                            ->relationship('obat', 'nama_obat')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Obat'),

                        Forms\Components\TextInput::make('jumlah')
                            ->numeric()
                            ->required()
                            ->label('Jumlah')
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                if ($state && $get('harga_beli')) {
                                    $subtotal = (int)$state * (int)$get('harga_beli');
                                    $set('subtotal', $subtotal);

                                    // Update total harga
                                    $repeaterState = $get('../../pembelianDetails');
                                    if ($repeaterState) {
                                        $totalHarga = 0;
                                        foreach ($repeaterState as $item) {
                                            $totalHarga += (int)($item['subtotal'] ?? 0);
                                        }
                                        $set('../../total_harga', $totalHarga);
                                    }
                                }
                            }),

                        Forms\Components\TextInput::make('harga_beli')
                            ->numeric()
                            ->required()
                            ->label('Harga Beli')
                            ->prefix('Rp')
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                if ($state && $get('jumlah')) {
                                    $subtotal = (int)$state * (int)$get('jumlah');
                                    $set('subtotal', $subtotal);

                                    // Update total harga
                                    $repeaterState = $get('../../pembelianDetails');
                                    if ($repeaterState) {
                                        $totalHarga = 0;
                                        foreach ($repeaterState as $item) {
                                            $totalHarga += (int)($item['subtotal'] ?? 0);
                                        }
                                        $set('../../total_harga', $totalHarga);
                                    }
                                }
                            }),

                        Forms\Components\TextInput::make('subtotal')
                            ->numeric()
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->default(0)
                            ->prefix('Rp')
                            ->formatStateUsing(fn ($state): string => $state ? number_format((int)$state, 0, ',', '.') : '0')
                            ->label('Subtotal'),
                    ])
                    ->columns(4)
                    ->label('Detail Pembelian')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $totalHarga = 0;
                            foreach ($state as $item) {
                                $totalHarga += (int)($item['subtotal'] ?? 0);
                            }
                            $set('total_harga', $totalHarga);
                        }
                    })
                    ->deleteAction(function (callable $set, $state) {
                        if ($state) {
                            $totalHarga = 0;
                            foreach ($state as $item) {
                                $totalHarga += (int)($item['subtotal'] ?? 0);
                            }
                            $set('total_harga', $totalHarga);
                        }
                    }),

                Forms\Components\TextInput::make('total_harga')
                    ->numeric()
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->default(0)
                    ->prefix('Rp')
                    ->formatStateUsing(fn ($state): string => $state ? number_format((int)$state, 0, ',', '.') : '0')
                    ->label('Total Harga'),

                Forms\Components\Textarea::make('keterangan')
                    ->maxLength(65535)
                    ->label('Keterangan')
                    ->placeholder('Tambahkan keterangan jika diperlukan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_faktur')
                    ->searchable()
                    ->sortable()
                    ->label('Nomor Faktur'),

                Tables\Columns\TextColumn::make('supplier.nama_supplier')
                    ->searchable()
                    ->sortable()
                    ->label('Supplier'),

                Tables\Columns\TextColumn::make('tanggal_pembelian')
                    ->date()
                    ->sortable()
                    ->label('Tanggal Pembelian'),

                Tables\Columns\TextColumn::make('total_harga')
                    ->money('IDR')
                    ->sortable()
                    ->label('Total Harga'),

                Tables\Columns\BadgeColumn::make('status_pembayaran')
                    ->colors([
                        'danger' => 'belum_lunas',
                        'success' => 'lunas',
                    ])
                    ->label('Status Pembayaran'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Tanggal Dibuat'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('supplier')
                    ->relationship('supplier', 'nama_supplier')
                    ->label('Filter Supplier'),

                Tables\Filters\SelectFilter::make('status_pembayaran')
                    ->options([
                        'lunas' => 'Lunas',
                        'belum_lunas' => 'Belum Lunas'
                    ])
                    ->label('Status Pembayaran'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPembelians::route('/'),
            'create' => Pages\CreatePembelian::route('/create'),
            'edit' => Pages\EditPembelian::route('/{record}/edit'),
        ];
    }
}
