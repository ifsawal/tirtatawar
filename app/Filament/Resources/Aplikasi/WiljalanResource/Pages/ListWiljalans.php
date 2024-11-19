<?php

namespace App\Filament\Resources\Aplikasi\WiljalanResource\Pages;

use App\Filament\Resources\Aplikasi\WiljalanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWiljalans extends ListRecords
{
    protected static string $resource = WiljalanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
