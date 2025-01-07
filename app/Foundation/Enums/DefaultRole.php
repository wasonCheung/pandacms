<?php

namespace App\Foundation\Enums;

enum DefaultRole: string
{
    case SuperAdmin = 'super-admin';

    case Admin = 'admin';

    case Member = 'member';

    /**
     * @param  DefaultGuard  $guard
     * @return DefaultRole[]
     */
    public static function guard(DefaultGuard $guard): array
    {
        return match ($guard) {
            DefaultGuard::Admin => [
                self::SuperAdmin,
                self::Admin,
            ],
            DefaultGuard::Portal => [
                self::Member,
            ],
        };
    }

    public static function admin():array
    {
        return self::guard(DefaultGuard::Admin);
    }

    public static function portal():array
    {
        return self::guard(DefaultGuard::Portal);
    }
}
