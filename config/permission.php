<?php
declare(strict_types=1);

use App\Foundation\Models\Permission;
use App\Foundation\Models\Role;

return [
    'models' => [
        'role' => Role::class,
        'permission' => Permission::class,
    ]
];
