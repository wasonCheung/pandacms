<?php

declare(strict_types=1);

namespace App\Foundation\Services;

use App\Foundation\Contracts\HasPermissions;
use App\Foundation\Entities\PermissionDO;
use App\Foundation\Enums\DefaultGuard;
use Illuminate\Support\Collection;

readonly class PermissionRegistry
{
    /**
     * @var Collection|PermissionDO[]
     */
    public Collection|array $permissions;

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
            if ($result instanceof PermissionDO) {
                $this->addPermission($result);

                return $this;
            }
        }
        $this->parseInterface($result ?? $class);

        return $this;
    }

    public function addPermission(PermissionDO $permission): self
    {
        $this->permissions->push($permission);

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
}
