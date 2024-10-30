<?php

namespace App\Filament\Resources\Website\KatagoriResource\Pages;

use App\Filament\Resources\Website\KatagoriResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKatagori extends CreateRecord
{
    protected static string $resource = KatagoriResource::class;


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Katagori sukses terdaftar...';
    }

}
