<?php

declare(strict_types=1);

namespace App\Admin\Panel\Resources\Role;

use App\Admin\Panel\ResourceTable;
use App\Foundation\Enums\DefaultGuard;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RoleTable extends ResourceTable
{
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

    public function permissionCountColumn(): Column
    {
        return TextColumn::make('permissions_count')
            ->badge()
            ->state(function ($record) {
                if ($record->isSuperAdminRole()) {
                    return '*';
                }

                return $record->permissions->count();
            })
            ->label(__class(__CLASS__, 'permissions_count'));
    }

    public function guardNameColumn(): Column
    {
        return TextColumn::make('guard_name')
            ->color(fn ($record) => match ($record->guard_name) {
                DefaultGuard::Admin->value => 'danger',
                DefaultGuard::Portal->value => 'success',
            })
            ->badge()
            ->state(fn ($record,
            ) => __model($record, 'guard_name')->fallback())
            ->label(__class(__CLASS__, 'guard_name'));
    }

    public function nameColumn(): Column
    {
        return TextColumn::make('name')
            ->state(fn ($record) => __model($record, 'name')->fallback())
            ->label(__class(__CLASS__, 'name'));
    }
}
