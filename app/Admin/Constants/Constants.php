<?php

declare(strict_types=1);

namespace App\Admin\Constants;

class Constants
{
    public const GUARD_NAME = 'admin';

    public const ROLE_SUPER_ADMIN = 'super-admin';

    public const ROLE_ADMIN = 'admin';

    public const DEFAULT_ROLES = [
        self::ROLE_SUPER_ADMIN,
        self::ROLE_ADMIN,
    ];
}
