<?php
declare(strict_types=1);

namespace App\Admin\Panel\Resources\User\Pages;

use App\Admin\Panel\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
