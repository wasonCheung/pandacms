<?php

declare(strict_types=1);

namespace App\Foundation\Services;

use App\Foundation\Contracts\HasPermissions;
use App\Foundation\Entities\PermissionBO;
use Illuminate\Support\Collection;

readonly class PermissionService
{
    public Collection $permissions;

    public function __construct()
    {
        $this->permissions = collect();
    }

    /**
     * @param  callable|class-string<HasPermissions>|array<HasPermissions>  $class
     * @return $this
     */
    public function register(callable|string|array $class): self
    {
        if (is_callable($class)) {
            $result = app()->call($class);
            if ($result instanceof PermissionBO) {
                $this->addPermission($result);

                return $this;
            }
        }
        $this->parseInterface($result ?? $class);
        return $this;
    }

    private function parseInterface(array|string $class): void
    {
        foreach ((array) $class as $item) {
            if (is_subclass_of($item, HasPermissions::class)) {
                $this->addPermission($item::definedPermissions());
            }
        }
    }

    public function addPermission(PermissionBO $permission): self
    {
        $this->permissions->push($permission);

        return $this;
    }
}
