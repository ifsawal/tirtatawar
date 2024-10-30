<?php

namespace App\Filament\Resources\Website\LayananResource\Pages;

use App\Filament\Resources\Website\LayananResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLayanan extends CreateRecord
{
    protected static string $resource = LayananResource::class;

    protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
}

