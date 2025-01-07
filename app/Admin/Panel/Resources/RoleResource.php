<?php

declare(strict_types=1);

namespace App\Admin\Panel\Resources;

use App\Admin\Panel\Resources\Role\Pages\CreateRole;
use App\Admin\Panel\Resources\Role\Pages\EditRole;
use App\Admin\Panel\Resources\Role\Pages\ListRoles;
use App\Admin\Panel\Resources\Role\RoleForm;
use App\Admin\Panel\Resources\Role\RoleTable;
use App\Foundation\Contracts\HasPermissions;
use App\Foundation\Models\Role;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class RoleResource extends Resource implements HasPermissions
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    public static function form(Form $form): Form
    {
        return RoleForm::make($form);
    }

    public static function table(Table $table): Table
    {
        return RoleTable::make($table);
    }

    public static function getModelLabel(): string
    {
        return (string) __class(__CLASS__, 'model_label');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }

    public static function definedPermissions(): array
    {
        return [
            'resource_role_view_any' => __class(__CLASS__, 'permissions.view_any'),
            'resource_role_view' => __class(__CLASS__, 'permissions.view'),
            'resource_role_create' => __class(__CLASS__, 'permissions.create'),
            'resource_role_edit' => __class(__CLASS__, 'permissions.edit'),
            'resource_role_delete' => __class(__CLASS__, 'permissions.delete'),
        ];
    }
}
