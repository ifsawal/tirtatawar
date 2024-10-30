<?php

namespace App\Filament\Resources\Website\FaqResource\Pages;

use App\Filament\Resources\Website\FaqResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFaq extends CreateRecord
{
    protected static string $resource = FaqResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Pertanyaan sukses disimpan...';
    }
}
