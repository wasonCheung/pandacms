<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Admin\Constants\Constants;
use App\Admin\Utils\RoleUtil;
use App\Foundation\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->clearPermisssionCache();
        $this->createAdminDefaultRoles();
        $this->createAdminUsers();
    }

    private function clearPermisssionCache(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    private function createAdminDefaultRoles(): void
    {
        Role::create([
            'name' => Constants::ROLE_SUPER_ADMIN,
            'guard_name' => Constants::GUARD_NAME,
        ]);

        Role::create([
            'name' => Constants::ROLE_ADMIN,
            'guard_name' => Constants::GUARD_NAME,
        ]);
    }

    private function createAdminUsers(): void
    {
        User::factory()->create([
            'name' => 'pandacms',
            'password' => Hash::make('123456'),
        ]);

        User::whereName('pandacms')->firstOrFail()->assignRole(RoleUtil::getSuperAdmin());

        User::factory()->create([
            'name' => 'admin',
            'password' => Hash::make('123456'),
        ]);

        User::whereName('admin')->firstOrFail()->assignRole(RoleUtil::getAdmin());
    }
}
