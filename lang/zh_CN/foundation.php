<?php

declare(strict_types=1);

use App\Foundation\Enums\DefaultGuard;

return [
    DefaultGuard::class => [
        DefaultGuard::Admin->name => '后台',
        DefaultGuard::Portal->name => '前台',
    ],
];
