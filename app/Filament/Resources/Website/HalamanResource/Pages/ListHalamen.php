<?php

namespace App\Filament\Resources\Website\HalamanResource\Pages;

use App\Filament\Resources\Website\HalamanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHalamen extends ListRecords
{
    protected static string $resource = HalamanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
