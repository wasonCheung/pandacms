<?php

declare(strict_types=1);

namespace App\Foundation\Entities;

use App\Foundation\Enums\DefaultGuard;

class PermissionDO
{
    public readonly string $category;

    public readonly string $group;

    /**
     * @var string[]
     */
    public readonly array $permissions;

    private mixed $categoryLabel;

    private mixed $groupLabel;

    private mixed $permissionsLabel;

    public function __construct(public DefaultGuard $guard) {}

    public static function admin(): PermissionDO
    {
        return new PermissionDO(DefaultGuard::Admin);
    }

    public static function portal(): PermissionDO
    {
        return new PermissionDO(DefaultGuard::Portal);
    }

    public function permissions(array $permissions): PermissionDO
    {
        $this->permissions = $permissions;

        return $this;
    }

    public function category(string $category): PermissionDO
    {
        $this->category = $category;

        return $this;
    }

    public function group(string $group): PermissionDO
    {
        $this->group = $group;

        return $this;
    }

    public function getCategoryLabel(): string
    {
        $call = $this->categoryLabel ??= $this->category;

        return is_callable($call) ? $call() : $call;
    }

    public function getGroupLabel(): string
    {
        $call = $this->groupLabel ??= $this->group;

        return is_callable($call) ? $call() : $call;
    }

    public function getPermissionsLabel(): array
    {
        $call = $this->permissionsLabel;

        return collect($this->permissions)
            ->mapWithKeys(fn (string $permission) => [$permission => $call($permission)])
            ->toArray();
    }

    public function categoryLabel(string|callable $categoryLabel): static
    {
        $this->categoryLabel = $categoryLabel;

        return $this;
    }

    public function permissionsLabel(callable $permissionsLabel): static
    {
        $this->permissionsLabel = $permissionsLabel;

        return $this;
    }

    public function groupLabel(string|callable $groupLabel): static
    {
        $this->groupLabel = $groupLabel;

        return $this;
    }
}
