<?php

declare(strict_types=1);

namespace App\Admin\Panel\Resources;

use App\Admin\Panel\Contracts\Resource;
use App\Foundation\Models\Role;

class RoleResource extends Resource
{
    public const PERMISSION_VIEW_ANY = 'resource_role_view_any';

    public const PERMISSION_VIEW = 'resource_role_view';

    public const PERMISSION_CREATE = 'resource_role_create';

    public const PERMISSION_UPDATE = 'resource_role_update';

    public const PERMISSION_DELETE = 'resource_role_delete';

    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
}
