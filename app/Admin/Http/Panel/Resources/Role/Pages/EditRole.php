<?php

declare(strict_types=1);

namespace App\Admin\Panel\Resources\Role\Pages;

use App\Admin\Panel\Resources\RoleResource;
use App\Base\Enums\RolesName;
use App\Foundation\Models\Permission;
use App\Foundation\Models\Role;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Collection;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    public Collection $permissions;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->hidden(fn ($record) => $record->isDefaultRoles()),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $permissions = array_filter($data, function ($key) {
            return str_starts_with($key, 'permissions@');
        }, ARRAY_FILTER_USE_KEY);

        $this->permissions = collect($permissions)
            ->values()
            ->flatten()
            ->unique();

        return array_filter($data, function ($key) {
            return !str_starts_with($key, 'permissions@');
        }, ARRAY_FILTER_USE_KEY);
    }

    protected function afterSave(): void
    {
        $permissionModels = collect();
        $this->permissions->each(function ($permission) use ($permissionModels) {
            $permissionModels->push(Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => $this->data['guard_name'],
            ]));
        });

        /**
         * @var Role $record
         */
        $record = $this->record;

        $record->syncPermissions($permissionModels);
    }
}
