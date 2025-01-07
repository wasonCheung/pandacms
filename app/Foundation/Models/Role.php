<?php

namespace App\Foundation\Models;

use App\Foundation\Enums\DefaultGuard;
use App\Foundation\Enums\DefaultRole;
use Illuminate\Database\Eloquent\Builder;

class Role extends \Spatie\Permission\Models\Role
{
    public function scopeAdmin(Builder $query): Builder
    {
        return $query->where('guard_name', DefaultGuard::Admin);
    }

    public function scopePortal(Builder $query): Builder
    {
        return $query->where('guard_name', DefaultGuard::Portal);
    }

    public static function getSuperAdminRole(): Role
    {
        return Role::admin()->whereName(DefaultRole::SuperAdmin)->firstOrFail();
    }

    public static function getAdminRole(): Role
    {
        return Role::admin()->whereName(DefaultRole::Admin)->firstOrFail();
    }

    public static function getMemberRole(): Role
    {
        return Role::portal()->whereName(DefaultRole::Member)->firstOrFail();
    }

    public function isDefaultRole(): bool
    {
        return self::checkDefaultRole($this);
    }

    public static function checkDefaultRole(string|Role $role): bool
    {
        return collect(DefaultRole::cases())->contains(DefaultRole::tryFrom($role instanceof Role ? $role->name : $role));
    }

    public function isAdminGuard(): bool
    {
        return self::checkAdminGuard($this);
    }

    public static function checkAdminGuard(string|Role $role): bool
    {
        return DefaultGuard::tryFrom($role->guard_name) === DefaultGuard::Admin;
    }

    public function isPortalGuard(): bool
    {
        return self::checkPortalGuard($this);
    }

    public static function checkPortalGuard(string|Role $role): bool
    {
        return DefaultGuard::tryFrom($role->guard_name) === DefaultGuard::Portal;
    }

    public function isSuperAdminRole(): bool
    {
        return DefaultRole::tryFrom($this->name) === DefaultRole::SuperAdmin;
    }

    public function isAdminRole(): bool
    {
        return DefaultRole::tryFrom($this->name) === DefaultRole::Admin;
    }

    public function isMemberRole(): bool
    {
        return DefaultRole::tryFrom($this->name) === DefaultRole::Member;
    }
}
