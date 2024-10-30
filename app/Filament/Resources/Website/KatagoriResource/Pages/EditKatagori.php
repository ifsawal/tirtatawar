<?php

namespace App\Filament\Resources\Website\KatagoriResource\Pages;

use App\Filament\Resources\Website\KatagoriResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditKatagori extends EditRecord
{
    protected static string $resource = KatagoriResource::class;

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
            ->body('Perubahan katagori sukses...');
    }


}
