<?php

declare(strict_types=1);

namespace App\Foundation\Services;

use App\Foundation\Enums\DefaultRole;
use App\Foundation\Models\Role;

class RoleService
{
    public function getSuperAdmin(): Role
    {
        return Role::admin()->whereName(DefaultRole::SuperAdmin)->firstOrFail();
    }

    public function getAdmin(): Role
    {
        return Role::admin()->whereName(DefaultRole::Admin)->firstOrFail();
    }

    public function getMember(): Role
    {
        return Role::portal()->whereName(DefaultRole::Member)->firstOrFail();
    }

    public function isDefaultRole(Role $role): bool
    {
        return collect(DefaultRole::cases())->contains(DefaultRole::tryFrom($role->name));
    }

    public function isSuperAdmin(Role $role): bool
    {
        return DefaultRole::tryFrom($role->name) === DefaultRole::SuperAdmin;
    }

    public function isAdmin(Role $role): bool
    {
        return DefaultRole::tryFrom($role->name) === DefaultRole::Admin;
    }

    public function isMember(Role $role): bool
    {
        return DefaultRole::tryFrom($role->name) === DefaultRole::Member;
    }
}
