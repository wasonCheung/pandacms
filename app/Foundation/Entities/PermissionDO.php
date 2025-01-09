<?php

declare(strict_types=1);

namespace App\Foundation\Entities;

use App\Foundation\Enums\DefaultGuard;

readonly class PermissionDO
{
    public DefaultGuard $guard;

    public string $category;

    public string $group;

    public array $permissions;

    public function __construct(DefaultGuard $guard, string $category, string $group, array $permissions)
    {
        $this->guard = $guard;
        $this->category = $category;
        $this->group = $group;
        $this->permissions = $permissions;
    }
}
