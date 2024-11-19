<?php

namespace App\Filament\Resources\Aplikasi;

use Filament\Forms;
use Filament\Tables;

use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Master\Pelanggan;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Aplikasi\PelangganResource\Pages;
use App\Filament\Resources\Aplikasi\PelangganResource\RelationManagers;

class PelangganResource extends Resource
{
    protected static ?string $model = Pelanggan::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $navigationGroup = 'Aplikasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nik')
                    ->maxLength(255),
                Forms\Components\TextInput::make('kk')
                    ->maxLength(255),
                Forms\Components\TextInput::make('lat')
                    ->maxLength(255),
                Forms\Components\TextInput::make('long')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nolama')
                    ->maxLength(255),
                Select::make("pdam_id")
                    ->relationship('pdam', 'nama')->preload(),
                Select::make("desa_id")
                    ->relationship('desa', 'desa'),
                Select::make("user_id")
                    ->preload()
                    ->relationship('user', 'nama'),
                Select::make("golongan_id")
                    ->relationship('golongan', 'golongan'),
                Forms\Components\TextInput::make('wiljalan_id')
                    ->numeric(),
                Forms\Components\TextInput::make('rute_id')
                    ->numeric(),
                Forms\Components\TextInput::make('user_id_penyetuju')
                    ->numeric(),
                Forms\Components\TextInput::make('user_id_perubahan')
                    ->numeric(),
                Forms\Components\TextInput::make('hp')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('penetapan')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('kode')
                    ->numeric(),
                Forms\Components\TextInput::make('user_id_petugas')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Nopel')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nik')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('long')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nolama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pdam.nama')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('desa.desa')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.nama')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('golongan.golongan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('wiljalan.jalan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rute.rute')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_penyetuju.nama')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_perubahan.nama')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hp')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('penetapan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kode')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('petugas.nama')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPelanggans::route('/'),
            'create' => Pages\CreatePelanggan::route('/create'),
            'edit' => Pages\EditPelanggan::route('/{record}/edit'),
        ];
    }
}
