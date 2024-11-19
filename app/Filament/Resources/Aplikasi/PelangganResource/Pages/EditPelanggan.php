<?php

namespace App\Filament\Resources\Aplikasi\PelangganResource\Pages;

use App\Filament\Resources\Aplikasi\PelangganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPelanggan extends EditRecord
{
    protected static string $resource = PelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
