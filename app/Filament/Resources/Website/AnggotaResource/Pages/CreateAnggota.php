<?php

namespace App\Filament\Resources\Website\AnggotaResource\Pages;

use App\Filament\Resources\Website\AnggotaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAnggota extends CreateRecord
{
    protected static string $resource = AnggotaResource::class;


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Anggota sukses terdaftar...';
    }
}
