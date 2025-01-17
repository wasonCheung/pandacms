<?php

declare(strict_types=1);

namespace App\Foundation\Services;

use App\Foundation\Enums\DefaultRole;
use App\Foundation\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function getAdminUsersList(): Collection
    {
        return User::role(DefaultRole::Admin)->get();
    }

    public function getSuperAdminUsersList(): Collection
    {
        return User::role(DefaultRole::SuperAdmin)->get();
    }

    public function getMemberUsersList(): Collection
    {
        return User::role(DefaultRole::Member)->get();
    }

    public function hasAdminModuleRole(User $user): bool
    {
        return $user->roles()->admin()->first() !== null;
    }

    public function hasPortalModuleRole(): bool
    {
        return User::roles()->portal()->first() !== null;
    }
}
