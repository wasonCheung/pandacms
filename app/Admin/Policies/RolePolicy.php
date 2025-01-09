<?php

declare(strict_types=1);

namespace App\Admin\Policies;

use App\Foundation\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view roles');
    }
}
