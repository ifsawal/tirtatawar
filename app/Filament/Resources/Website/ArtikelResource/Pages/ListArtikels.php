<?php

namespace App\Filament\Resources\Website\ArtikelResource\Pages;

use App\Filament\Resources\Website\ArtikelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArtikels extends ListRecords
{
    protected static string $resource = ArtikelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
