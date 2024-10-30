<?php

namespace App\Filament\Resources\Website\HalamanResource\Pages;

use App\Filament\Resources\Website\HalamanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateHalaman extends CreateRecord
{
    protected static string $resource = HalamanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Halaman sukses disimpan...';
    }
}
