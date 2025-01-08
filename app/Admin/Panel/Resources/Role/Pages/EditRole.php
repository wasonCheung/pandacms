<?php

declare(strict_types=1);

namespace App\Admin\Panel\Resources\Role\Pages;

use App\Admin\Panel\Resources\RoleResource;
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
                ->hidden(fn (Role $record) => $record->isDefaultRole()),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->permissions = collect($data['permissions'] ?? [])
            ->values()
            ->flatten()
            ->unique();

        unset($data['permissions']);

        return $data;
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
