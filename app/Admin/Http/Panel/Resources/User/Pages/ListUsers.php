<?php

namespace App\Admin\Panel\Resources\User\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Admin\Panel\Resources\UserResource;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
