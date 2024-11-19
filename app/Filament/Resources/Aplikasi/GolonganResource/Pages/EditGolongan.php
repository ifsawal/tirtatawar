<?php

namespace App\Filament\Resources\Aplikasi\GolonganResource\Pages;

use App\Filament\Resources\Aplikasi\GolonganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGolongan extends EditRecord
{
    protected static string $resource = GolonganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
