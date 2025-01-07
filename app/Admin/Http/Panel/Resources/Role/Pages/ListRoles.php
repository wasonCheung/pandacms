<?php

namespace App\Admin\Panel\Resources\Role\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Admin\Panel\Resources\RoleResource;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
