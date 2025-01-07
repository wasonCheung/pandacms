<?php
declare(strict_types=1);

namespace App\Admin\Panel\Resources\User\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Admin\Panel\Resources\UserResource;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
