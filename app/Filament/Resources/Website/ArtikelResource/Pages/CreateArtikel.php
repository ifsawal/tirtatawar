<?php

namespace App\Filament\Resources\Website\ArtikelResource\Pages;

use App\Filament\Resources\Website\ArtikelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateArtikel extends CreateRecord
{
    protected static string $resource = ArtikelResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Artikel sukses terdaftar...';
    }
}
