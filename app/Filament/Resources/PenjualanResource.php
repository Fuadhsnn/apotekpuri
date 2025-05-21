<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Models\Penjualan;
use App\Models\Obat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                Forms\Components\TextInput::make('nomor_nota')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->disabled()
                    ->dehydrated()
                    ->default(function () {
                        // Mendapatkan tanggal saat ini
                        $tanggal = now()->format('Ymd');

                        // Mendapatkan nomor urut terakhir
                        $lastPenjualan = Penjualan::whereDate('created_at', now())
                            ->orderBy('nomor_nota', 'desc')
                            ->first();

                        $nomorUrut = '001';

                        if ($lastPenjualan) {
                            $lastNomor = substr($lastPenjualan->nomor_nota, -3);
                            $nomorUrut = str_pad((int)$lastNomor + 1, 3, '0', STR_PAD_LEFT);
                        }

                        return "PJ/{$tanggal}/{$nomorUrut}";
                    })
                    ->label('Nomor Nota')
                    ->placeholder('Nomor nota akan dibuat otomatis')
                    ->helperText('Format: PJ/YYYYMMDD/XXX'),

                Forms\Components\DatePicker::make('tanggal_penjualan')
                    ->required()
                    ->label('Tanggal Penjualan')
                    ->default(now())
                    ->disabled()
                    ->dehydrated(),

                Forms\Components\Repeater::make('penjualanDetails')
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
                                if ($state && $get('harga_jual')) {
                                    $subtotal = (int)$state * (int)$get('harga_jual');
                                    $set('subtotal', $subtotal);

                                    // Update total harga
                                    $repeaterState = $get('../../penjualanDetails');
                                    if ($repeaterState) {
                                        $totalHarga = 0;
                                        foreach ($repeaterState as $item) {
                                            $totalHarga += (int)($item['subtotal'] ?? 0);
                                        }
                                        $set('../../total_harga', $totalHarga);
                                    }
                                }
                            }),

                        Forms\Components\TextInput::make('harga_jual')
                            ->numeric()
                            ->required()
                            ->label('Harga Jual')
                            ->prefix('Rp')
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                if ($state && $get('jumlah')) {
                                    $subtotal = (int)$state * (int)$get('jumlah');
                                    $set('subtotal', $subtotal);

                                    // Update total harga
                                    $repeaterState = $get('../../penjualanDetails');
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
                            ->formatStateUsing(fn($state): string => $state ? number_format((int)$state, 0, ',', '.') : '0')
                            ->label('Subtotal'),
                    ])
                    ->columns(4)
                    ->label('Detail Penjualan')
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
                    ->formatStateUsing(fn($state): string => $state ? number_format((int)$state, 0, ',', '.') : '0')
                    ->label('Total Harga'),

                Forms\Components\TextInput::make('bayar')
                    ->numeric()
                    ->required()
                    ->label('Bayar')
                    ->prefix('Rp')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        if ($state) {
                            $kembalian = (int)$state - (int)$get('total_harga');
                            $set('kembalian', $kembalian);
                        }
                    }),

                Forms\Components\TextInput::make('kembalian')
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->default(0)
                    ->prefix('Rp')
                    ->formatStateUsing(fn($state): string => $state ? number_format((int)$state, 0, ',', '.') : '0')
                    ->label('Kembalian'),

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
                Tables\Columns\TextColumn::make('nomor_nota')
                    ->searchable()
                    ->sortable()
                    ->label('Nomor Nota'),

                Tables\Columns\TextColumn::make('tanggal_penjualan')
                    ->date()
                    ->sortable()
                    ->label('Tanggal Penjualan'),

                Tables\Columns\TextColumn::make('total_harga')
                    ->money('IDR')
                    ->sortable()
                    ->label('Total Harga'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Tanggal Dibuat'),
            ])
            ->filters([
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
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}
