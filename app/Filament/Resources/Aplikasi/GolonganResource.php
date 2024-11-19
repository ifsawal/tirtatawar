<?php

namespace App\Filament\Resources\Aplikasi;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Master\Golongan;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Aplikasi\GolonganResource\Pages;
use App\Filament\Resources\Aplikasi\GolonganResource\RelationManagers;

class GolonganResource extends Resource
{
    protected static ?string $model = Golongan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Aplikasi';
    protected static ?string $navigationParentItem = 'Pelanggan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('golongan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('jenis')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('harga')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('biaya')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('pajak')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('denda')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('pdam_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('golongan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis')
                    ->searchable(),
                Tables\Columns\TextColumn::make('harga')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('biaya')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pajak')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('denda')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pdam_id')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListGolongans::route('/'),
            'create' => Pages\CreateGolongan::route('/create'),
            'edit' => Pages\EditGolongan::route('/{record}/edit'),
        ];
    }
}
