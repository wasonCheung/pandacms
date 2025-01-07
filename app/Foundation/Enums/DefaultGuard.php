<?php
declare(strict_types=1);

namespace App\Foundation\Enums;

enum DefaultGuard: string
{
    case Admin = 'admin';
    case Portal = 'portal';
}
