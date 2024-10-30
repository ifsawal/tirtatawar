<?php

namespace App\Filament\Resources\Role\PermissionResource\Pages;

use App\Filament\Resources\Role\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;
}
