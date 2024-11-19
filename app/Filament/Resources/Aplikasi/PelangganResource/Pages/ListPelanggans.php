<?php

namespace App\Filament\Resources\Aplikasi\PelangganResource\Pages;

use App\Filament\Resources\Aplikasi\PelangganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPelanggans extends ListRecords
{
    protected static string $resource = PelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
