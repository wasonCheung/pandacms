<?php

declare(strict_types=1);

namespace App\Admin\Policies;

use App\Admin\Panel\Resources\RoleResource;
use App\Foundation\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo(RoleResource::PERMISSION_VIEW_ANY);
    }

    public function view(User $user): bool
    {
        return $user->checkPermissionTo(RoleResource::PERMISSION_VIEW);
    }

    public function create(User $user): bool
    {
        return $user->checkPermissionTo(RoleResource::PERMISSION_CREATE);
    }

    public function update(User $user): bool
    {
        return $user->checkPermissionTo(RoleResource::PERMISSION_UPDATE);
    }

    public function delete(User $user): bool
    {
        return $user->checkPermissionTo(RoleResource::PERMISSION_DELETE);
    }
}
