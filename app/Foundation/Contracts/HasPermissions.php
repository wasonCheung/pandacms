<?php
declare(strict_types=1);

namespace App\Foundation\Contracts;

use App\Foundation\Entities\PermissionDO;

interface HasPermissions
{
    public static function definedPermissions(): PermissionDO;
}
