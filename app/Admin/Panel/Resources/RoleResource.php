<?php

declare(strict_types=1);

namespace App\Admin\Panel\Resources;

use App\Admin\Panel\Contracts\Resource;
use App\Admin\Panel\Resources\Role\Pages\CreateRole;
use App\Admin\Panel\Resources\Role\Pages\EditRole;
use App\Admin\Panel\Resources\Role\Pages\ListRoles;
use App\Admin\Panel\Resources\Role\RoleForm;
use App\Admin\Panel\Resources\Role\RoleTable;
use App\Foundation\Entities\PermissionDO;
use App\Foundation\Models\Role;
use Filament\Forms\Form;
use Filament\Tables\Table;

class RoleResource extends Resource
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

    public static function definedPermissions(): PermissionDO
    {
        return parent::definedPermissions()
            ->permissions([
                'resource_role_view_any',
                'resource_role_view',
                'resource_role_create',
                'resource_role_edit',
                'resource_role_delete',
            ]);
    }
}
