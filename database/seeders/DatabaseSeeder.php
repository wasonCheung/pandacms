<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Foundation\Enums\DefaultGuard;
use App\Foundation\Enums\DefaultRole;
use App\Foundation\Models\Role;
use App\Foundation\Models\User;
use App\Foundation\Services\RoleService;
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
        $this->createDefaultRoles();
        $this->createAdminUsers();
        User::factory(50)->create();
    }

    private function clearPermisssionCache(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    private function createDefaultRoles(): void
    {
        foreach (DefaultRole::admin() as $role) {
            Role::create([
                'name' => $role->value,
                'guard_name' => DefaultGuard::Admin,
            ]);
        }

        foreach (DefaultRole::portal() as $role) {
            Role::create([
                'name' => $role->value,
                'guard_name' => DefaultGuard::Portal,
            ]);
        }
    }

    private function createAdminUsers(): void
    {
        User::factory()->create([
            'name' => 'pandacms',
            'password' => Hash::make('123456'),
        ]);

        User::whereName('pandacms')->firstOrFail()->assignRole(app(RoleService::class)->getSuperAdmin());

        User::factory()->create([
            'name' => 'admin',
            'password' => Hash::make('123456'),
        ]);

        User::whereName('admin')->firstOrFail()->assignRole(app(RoleService::class)->getAdmin());
    }
}
