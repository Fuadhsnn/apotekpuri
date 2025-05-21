<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Supplier';
    protected static ?string $modelLabel = 'Supplier';
    protected static ?string $pluralModelLabel = 'Supplier';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_supplier')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Supplier'),
                
                Forms\Components\TextInput::make('telepon')
                    ->tel()
                    ->required()
                    ->maxLength(15)
                    ->label('Nomor Telepon')
                    ->telRegex('/^[0-9\+\-\(\)\/\s]*$/')
                    ->placeholder('Format: 08xxxxxxxxxx'),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255)
                    ->label('Email')
                    ->placeholder('contoh@email.com'),

                Forms\Components\Textarea::make('alamat')
                    ->required()
                    ->maxLength(65535)
                    ->label('Alamat Lengkap')
                    ->rows(3),

                Forms\Components\Textarea::make('keterangan')
                    ->maxLength(65535)
                    ->label('Keterangan')
                    ->placeholder('Tambahkan keterangan jika diperlukan')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_supplier')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Supplier'),
                
                Tables\Columns\TextColumn::make('telepon')
                    ->searchable()
                    ->label('Nomor Telepon'),
                
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->label('Email'),
                
                Tables\Columns\TextColumn::make('alamat')
                    ->searchable()
                    ->limit(50)
                    ->label('Alamat'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Tanggal Dibuat')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Terakhir Diupdate')
                    ->toggleable(isToggledHiddenByDefault: true),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
