<?php

declare(strict_types=1);

namespace App\Foundation\Entities;

use App\Foundation\Enums\DefaultGuard;
use Illuminate\Support\Collection;

readonly class PermissionBO
{
    public string $category;

    public string $group;

    /**
     * @var string[]
     */
    public array $permissions;

    public function __construct(public DefaultGuard $guard) {}

    public function permissions(array $permissions): PermissionBO
    {
        $this->permissions = $permissions;

        return $this;
    }

    public function category(string $category): PermissionBO
    {
        $this->category = $category;

        return $this;
    }

    public function group(string $group): PermissionBO
    {
        $this->group = $group;

        return $this;
    }

    public function getCategoryLabel(): TranslationDO
    {
        return TranslationDO::make($this->parseCategoryLabelKey())
            ->fallback($this->category);
    }

    public function getGroupLabel(): TranslationDO
    {
        return TranslationDO::make($this->parseGroupLabelKey())
            ->fallback($this->group);
    }

    public function getPermissionsLabel(): Collection
    {
        return TranslationDO::makeList($this->parseGroupKey(), $this->permissions);
    }

    public function parseCategoryLabelKey(): string
    {
        return $this->parseCategoryKey().'.label';
    }

    public function parseCategoryKey(): string
    {
        return "{$this->parseGuardKey()}.{$this->category}";
    }

    public function parseGroupKey(): string
    {
        return "{$this->parseCategoryKey()}.{$this->group}";
    }

    public function parseGroupLabelKey(): string
    {
        return $this->parseGroupKey().'.label';
    }

    public function parseGuardKey(): string
    {
        return "{$this->guard->value}.permissions";
    }

    public static function admin(): PermissionBO
    {
        return new PermissionBO(DefaultGuard::Admin);
    }

    public static function portal(): PermissionBO
    {
        return new PermissionBO(DefaultGuard::Portal);
    }
}
