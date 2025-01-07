<?php

declare(strict_types=1);

namespace App\Admin\Panel\Resources;

use App\Shared\Models\User;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Admin\Panel\Resources\User\Pages\CreateUser;
use App\Admin\Panel\Resources\User\Pages\EditUser;
use App\Admin\Panel\Resources\User\Pages\ListUsers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                TextColumn::make('name')
                    ->label(__('admin/resources/user.table.name'))
                    ->searchable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.user.model_label');
    }

    public static function definedPermissions(): array
    {
        return [
            'resource_role_view_any' => __('admin.resources.role.permissions.view_any'),
            'resource_role_view' => __('admin.resources.role.permissions.view'),
            'resource_role_create' => __('admin.resources.role.permissions.create'),
            'resource_role_edit' => __('admin.resources.role.permissions.edit'),
            'resource_role_delete' => __('admin.resources.role.permissions.delete'),
        ];
    }
}
