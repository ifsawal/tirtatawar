<?php

namespace App\Filament\Resources\Website\LayananResource\Pages;

use App\Filament\Resources\Website\LayananResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLayanans extends ListRecords
{
    protected static string $resource = LayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
