<?php

declare(strict_types=1);

namespace App\Foundation\Entities;

use App\Foundation\Enums\DefaultGuard;

class PermissionDO
{
    private string $category;

    private string $group;

    private array $permissions;

    private ?string $categoryLabel = null;

    private ?string $groupLabel = null;

    private array $permissionsLabel = [];

    private mixed $categoryLabelUsing = null;

    private mixed $groupLabelUsing = null;

    private mixed $permissionsLabelUsing = null;

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

    public function categoryLabel(?string $categoryLabel): self
    {
        $this->categoryLabel = $categoryLabel;
        return $this;
    }

    public function permissionsLabelUsing(mixed $permissionsLabelUsing): self
    {
        $this->permissionsLabelUsing = $permissionsLabelUsing;
        return $this;
    }

    public function groupLabelUsing(mixed $groupLabelUsing): self
    {
        $this->groupLabelUsing = $groupLabelUsing;
        return $this;
    }

    public function categoryLabelUsing(mixed $categoryLabelUsing): self
    {
        $this->categoryLabelUsing = $categoryLabelUsing;
        return $this;
    }

    public function permissionsLabel(array $permissionsLabel): self
    {
        $this->permissionsLabel = $permissionsLabel;
        return $this;
    }

    public function groupLabel(?string $groupLabel): self
    {
        $this->groupLabel = $groupLabel;
        return $this;
    }
}
