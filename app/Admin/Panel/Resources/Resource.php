<?php

declare(strict_types=1);

namespace App\Admin\Panel\Resources;

use App\Foundation\Contracts\HasPermissions;
use App\Foundation\Entities\PermissionBO;

class Resource extends \Filament\Resources\Resource implements HasPermissions
{
    public const PERMISSION_CATEGORY = 'resources';

    public static function definedPermissions(): PermissionBO
    {
        return PermissionBO::admin()
            ->category(static::PERMISSION_CATEGORY)
            ->group(static::class);
    }
}
