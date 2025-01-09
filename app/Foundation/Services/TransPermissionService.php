<?php

declare(strict_types=1);

namespace App\Foundation\Services;

use App\Foundation\Entities\PermissionDO;
use App\Foundation\Entities\TransDO;

class TransPermissionService
{
    public const DEFAULT_GROUP = 'permissions';

    public function transDO(PermissionDO $do): PermissionDO
    {
        $category = (string) $this->trans($do->getCategory());

        $group = (string) $this->trans("{$do->getCategory()}.{$do->getGroup()}");

        return PermissionDO::make($do->guard)
            ->group($group)
            ->category($category);
    }

    public function trans(?string $key = null): TransDO
    {
        if ($key === null) {
            return TransDO::make(self::DEFAULT_GROUP);
        }

        return TransDO::make($this->parseKey($key));
    }

    public function parseKey(string $key): string
    {
        return self::DEFAULT_GROUP.'.'.$key;
    }
}
