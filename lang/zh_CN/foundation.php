<?php
declare(strict_types=1);

use App\Foundation\Enums\DefaultGuard;
use App\Foundation\Enums\DefaultRole;

return [
    DefaultRole::class => [
        DefaultRole::Admin->name => '管理员',
        DefaultRole::SuperAdmin->name => '超级管理员',
        DefaultRole::Member->name => '会员',
    ],
    DefaultGuard::class => [
        DefaultGuard::Admin->name => '后台',
        DefaultGuard::Portal->name => '前台',
    ]
];
