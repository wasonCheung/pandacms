<?php

declare(strict_types=1);

namespace App\Admin\Panel\Resources;

use App\Admin\Panel\Contracts\Resource;
use App\Admin\Panel\Resources\User\Pages\CreateUser;
use App\Admin\Panel\Resources\User\Pages\EditUser;
use App\Admin\Panel\Resources\User\Pages\ListUsers;
use App\Admin\Panel\Resources\User\UserForm;
use App\Admin\Panel\Resources\User\UserTable;
use App\Foundation\Entities\PermissionDO;
use App\Foundation\Models\User;
use Filament\Forms\Form;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function table(Table $table): Table
    {
        return UserTable::make($table);
    }

    public static function form(Form $form): Form
    {
        return UserForm::make($form);
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
        return (string) __class(__CLASS__.'.model_label');
    }

    public static function definedPermissions(): PermissionDO
    {
        return parent::definedPermissions()
            ->permissions([
                'resource_user_view_any',
                'resource_user_view',
                'resource_user_create',
                'resource_user_edit',
                'resource_user_delete',
            ]);
    }
}
