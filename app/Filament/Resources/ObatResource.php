<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ObatResource\Pages;
use App\Models\Obat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ObatResource extends Resource
{
    protected static ?string $model = Obat::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static array $kategoriKode = [
        'tablet' => 'TB',
        'kapsul' => 'KP',
        'sirup' => 'SR',
        'strip' => 'SP',
        'botol' => 'BT',
        'box' => 'BX',
        'ampul' => 'AM',
        'salep' => 'SL',
        'drops' => 'DR',
        'inhaler' => 'IN',
        'suppositoria' => 'SP',
        'cream' => 'CR',
        'gel' => 'GL',
    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode_obat')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('Kode akan dibuat otomatis')
                    ->helperText('Format: AA(Kategori)BBB(Produsen)-CC(Tahun)-DDD(Nomor Urut)')
                    ->disabled()
                    ->dehydrated()
                    ->default(function () {
                        // Mendapatkan tahun saat ini (2 digit terakhir)
                        $tahun = date('y');

                        // Mendapatkan nomor urut terakhir
                        $lastObat = Obat::orderBy('kode_obat', 'desc')->first();
                        $nomorUrut = '001';

                        if ($lastObat) {
                            $lastNomor = substr($lastObat->kode_obat, -3);
                            $nomorUrut = str_pad((int)$lastNomor + 1, 3, '0', STR_PAD_LEFT);
                        }

                        // Kode produsen (contoh: menggunakan 001)
                        $produsen = '001';

                        return "XX{$produsen}-{$tahun}-{$nomorUrut}";
                    }),
                Forms\Components\TextInput::make('nama_obat')
                    ->required(),
                Forms\Components\Textarea::make('deskripsi'),
                Forms\Components\Select::make('kategori')
                    ->options([
                        'tablet' => 'Tablet',
                        'kapsul' => 'Kapsul',
                        'sirup' => 'Sirup',
                        'strip' => 'Strip',
                        'botol' => 'Botol',
                        'box' => 'Box',
                        'ampul' => 'Ampul',
                        'salep' => 'Salep',
                        'drops' => 'Drops (Tetes)',
                        'inhaler' => 'Inhaler',
                        'suppositoria' => 'Suppositoria',
                        'cream' => 'Cream',
                        'gel' => 'Gel',
                    ])
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        if ($state) {
                            $kodeKategori = static::$kategoriKode[$state] ?? 'XX';
                            $currentKode = $get('kode_obat');
                            $newKode = substr_replace($currentKode, $kodeKategori, 0, 2);
                            $set('kode_obat', $newKode);
                        }
                    }),
                Forms\Components\TextInput::make('stok')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('harga_beli')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('harga_jual')
                    ->numeric()
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_kadaluarsa')
                    ->required(),

                Forms\Components\FileUpload::make('gambar')
                    ->image()
                    ->imageEditor()
                    ->required()
                    ->directory('obat-images')
                    ->label('Gambar Obat')
                    ->helperText('Upload gambar obat')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_obat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_obat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kategori')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stok')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga_jual')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_kadaluarsa')
                    ->date()
                    ->sortable(),

                Tables\Columns\ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-medicine.png')),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListObats::route('/'),
            'create' => Pages\CreateObat::route('/create'),
            'edit' => Pages\EditObat::route('/{record}/edit'),
        ];
    }
}
