<?php

namespace App\Filament\Resources\Aplikasi;

use Filament\Forms;
use Filament\Tables;

use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Master\Wiljalan;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Aplikasi\WiljalanResource\Pages;
use App\Filament\Resources\Aplikasi\WiljalanResource\RelationManagers;

class WiljalanResource extends Resource
{
    protected static ?string $model = Wiljalan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Aplikasi';
    protected static ?string $navigationParentItem = 'Pelanggan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('jalan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('user_id')
                    ->numeric(),
                Forms\Components\TextInput::make('mulai')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('akhir')
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
                Tables\Columns\TextColumn::make('jalan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mulai')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('akhir')
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
            'index' => Pages\ListWiljalans::route('/'),
            'create' => Pages\CreateWiljalan::route('/create'),
            'edit' => Pages\EditWiljalan::route('/{record}/edit'),
        ];
    }
}
