<?php

declare(strict_types=1);

namespace App\Admin\Utils;

use App\Admin\Constants\Constants;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

class RoleUtil
{
    public static function getAdmin(): Role
    {
        return self::builder()->where('name', Constants::ROLE_ADMIN)->first();
    }

    public static function builder(): Builder
    {
        return Role::whereGuardName(Constants::GUARD_NAME);
    }

    public static function getSuperAdmin(): Role
    {
        return self::builder()->where('name', Constants::ROLE_SUPER_ADMIN)->first();
    }

    public static function getAll(): Collection
    {
        return self::builder()->get();
    }

    public static function isDefaultRole(Role|string $role): bool
    {
        return in_array($role instanceof Role ? $role->name : $role,
            [Constants::ROLE_ADMIN, Constants::ROLE_SUPER_ADMIN]);
    }
}
