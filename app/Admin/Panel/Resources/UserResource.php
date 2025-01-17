<?php

declare(strict_types=1);

namespace App\Admin\Panel\Resources;

use App\Admin\Panel\Contracts\Resource;
use App\Foundation\Models\User;

class UserResource extends Resource
{
    public const PERMISSION_VIEW_ANY = 'resource_user_view_any';

    public const PERMISSION_VIEW = 'resource_user_view';

    public const PERMISSION_CREATE = 'resource_user_create';

    public const PERMISSION_UPDATE = 'resource_user_edit';

    public const PERMISSION_DELETE = 'resource_user_delete';

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
}
