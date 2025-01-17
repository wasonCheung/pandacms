<?php

declare(strict_types=1);

use App\Foundation\Enums\DefaultRole;

return [
    'role' => [
        'name' => [
            DefaultRole::Admin->value => '管理员',
            DefaultRole::SuperAdmin->value => '超级管理员',
            DefaultRole::Member->value => '会员',
        ],
    ],
];
