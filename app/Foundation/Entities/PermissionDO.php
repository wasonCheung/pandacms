<?php

declare(strict_types=1);

namespace App\Foundation\Entities;

use App\Foundation\Enums\DefaultGuard;

class PermissionDO
{
    private string $category;

    private string $group;

    private array $permissions;

    public function __construct(public DefaultGuard $guard) {}

    public static function make(DefaultGuard $guard): PermissionDO
    {
        return new self($guard);
    }

    public function permissions(array $permissions): self
    {
        $this->permissions = $permissions;

        return $this;
    }

    public function category(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function group(string $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function getGroup(): string
    {
        return $this->group;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function getCategory(): string
    {
        return $this->category;
    }
}
