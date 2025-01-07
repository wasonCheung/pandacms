<?php
declare(strict_types=1);

namespace App\Admin\Panel\Resources\User\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Admin\Panel\Resources\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
