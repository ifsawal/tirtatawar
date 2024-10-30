<?php

namespace App\Filament\Resources\Website\KatagoriResource\Pages;

use App\Filament\Resources\Website\KatagoriResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKatagoris extends ListRecords
{
    protected static string $resource = KatagoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
