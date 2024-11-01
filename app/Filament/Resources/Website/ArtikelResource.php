<?php

namespace App\Filament\Resources\Website;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\Website\Artikel;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Website\ArtikelResource\Pages;
use App\Filament\Resources\Website\ArtikelResource\RelationManagers;
use App\Models\Website\Katagori;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;

class ArtikelResource extends Resource
{
    protected static ?string $model = Artikel::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Blog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('judul')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->readOnly()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('katagori_id')->options(Katagori::all()->pluck('nama', 'id'))
                    ->label("Katagori")
                    ->required(),
                Forms\Components\Hidden::make('admin_id')
                    ->default(auth()->id()),
                Forms\Components\FileUpload::make('image')
                    ->imageEditor()
                    ->image(),
                Forms\Components\RichEditor::make('konten')
                    // ->disableToolbarButtons(['attachFiles'])
                    ->fileAttachmentsDisk(0)
                    ->fileAttachmentsDirectory('attachments')
                    ->fileAttachmentsVisibility('private')
                    ->columnSpanFull(),
                Select::make('status')->options(
                    [
                        1 => "Aktif",
                        0 => "Nonaktif",
                    ]
                )
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('katagori_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('admin_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('status')
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
            'index' => Pages\ListArtikels::route('/'),
            'create' => Pages\CreateArtikel::route('/create'),
            'edit' => Pages\EditArtikel::route('/{record}/edit'),
        ];
    }
}
