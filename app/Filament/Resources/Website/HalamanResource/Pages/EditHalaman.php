<?php

namespace App\Filament\Resources\Website\HalamanResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Website\HalamanResource;

class EditHalaman extends EditRecord
{
    protected static string $resource = HalamanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Perubahan.')
            ->body('Perubahan halaman sukses...');
    }
}
