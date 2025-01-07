<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Foundation\Enums\DefaultGuard;
use App\Foundation\Enums\DefaultRole;
use App\Foundation\Models\Role;
use App\Foundation\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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
        foreach (DefaultRole::admin() as $role) {
            Role::create([
                'name' => $role->value,
                'guard_name' => DefaultGuard::Admin,
            ]);
        }
    }

    private function createAdminUsers(): void
    {
        User::factory()->create([
            'name' => 'pandacms',
            'password' => Hash::make('123456'),
        ]);

        User::whereName('pandacms')->firstOrFail()->assignRole(Role::getSuperAdmin());

        User::factory()->create([
            'name' => 'admin',
            'password' => Hash::make('123456'),
        ]);

        User::whereName('admin')->firstOrFail()->assignRole(Role::getAdmin());
    }
}
