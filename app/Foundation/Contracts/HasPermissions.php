<?php
declare(strict_types=1);

namespace App\Foundation\Contracts;

interface HasPermissions
{
    /**
     * @return array[$permission => $translation]
     */
    public static function definedPermissions(): array;
}
