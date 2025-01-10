<?php

declare(strict_types=1);

namespace App\Admin\Panel\Contracts;

use App\Foundation\Contracts\HasPermissions;
use App\Foundation\Entities\PermissionDO;

abstract class Resource extends \Filament\Resources\Resource implements HasPermissions
{
    public const PERMISSION_CATEGORY = 'resources';

    public static function definedPermissions(): PermissionDO
    {
        return PermissionDO::admin()
            ->category(self::PERMISSION_CATEGORY)
            ->categoryLabel((string) __class(__CLASS__))
            ->group(static::class)
            ->groupLabel(static::getModelLabel())
            ->permissionsLabel(function (string $permission) {
                return (string) __class(static::class.'.permissions.'.$permission);
            });
    }
}
