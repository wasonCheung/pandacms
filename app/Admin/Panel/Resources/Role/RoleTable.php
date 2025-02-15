<?php

declare(strict_types=1);

namespace App\Admin\Panel\Resources\Role;

use App\Admin\Panel\Contracts\ResourceTable;
use App\Foundation\Enums\DefaultGuard;
use App\Foundation\Services\RoleService;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RoleTable extends ResourceTable
{
    public function __construct(protected RoleService $roleService) {}

    public function buildTable(): Table
    {
        return $this->table
            ->defaultSort('guard_name')
            ->striped()
            ->paginated(false)
            ->columns([
                $this->nameColumn(),
                $this->guardNameColumn(),
                $this->permissionCountColumn(),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }

    public function nameColumn(): Column
    {
        return TextColumn::make('name')
            ->state(fn ($record) => __model($record, 'name'))
            ->label(__class(__CLASS__, 'name'));
    }

    public function guardNameColumn(): Column
    {
        return TextColumn::make('guard_name')
            ->color(fn ($record) => DefaultGuard::tryFrom($record->guard_name)->getColor())
            ->badge()
            ->state(fn ($record) => DefaultGuard::tryFrom($record->guard_name)->getLabel())
            ->label(__class(__CLASS__, 'guard_name'));
    }

    public function permissionCountColumn(): Column
    {
        return TextColumn::make('permissions_count')
            ->badge()
            ->state(function ($record) {
                if ($this->roleService->isSuperAdmin($record)) {
                    return '*';
                }

                return $record->permissions->count();
            })
            ->label(__class(__CLASS__, 'permissions_count'));
    }
}
