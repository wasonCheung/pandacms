<?php

declare(strict_types=1);

namespace App\Admin\Panel\Contracts;

use App\Admin\Panel\Resources\Role\Pages\CreateRole;
use App\Admin\Panel\Resources\Role\Pages\EditRole;
use App\Foundation\Contracts\HasPermissions;
use App\Foundation\Entities\PermissionDO;
use App\Foundation\Entities\TranslationDO;
use Filament\Forms\Form;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use ReflectionClass;

abstract class Resource extends \Filament\Resources\Resource implements HasPermissions
{
    public const PERMISSIONS_CATEGORY = 'resources';

    public const RESOURCES_NAMESPACE = 'App\\Admin\\Panel\\Resources\\';

    /**
     * @var class-string|ResourceTable
     */
    public static ?string $tableProvider = null;

    /**
     * @var class-string|ResourceForm
     */
    public static ?string $formProvider = null;

    public static function definedPermissions(): PermissionDO
    {
        return PermissionDO::admin()
            ->category(self::PERMISSIONS_CATEGORY)
            ->categoryLabel((string) __class(__CLASS__))
            ->group(static::class)
            ->groupLabel(static::getModelLabel())
            ->permissions(self::discoverPermissions())
            ->permissionsLabel(function (string $permission) {
                return (string) __class(static::class.'.'.$permission);
            });

    }

    public static function getModelLabel(): string
    {
        return (string) self::getTranslation('model_label');
    }

    public static function getTranslation(string $key): TranslationDO
    {
        return __class(static::class, $key);
    }

    public static function discoverPermissions(): array
    {
        $permissions = [];
        $reflection = new ReflectionClass(static::class);
        foreach ($reflection->getConstants() as $key => $value) {
            if (str_starts_with($key, 'PERMISSION_')) {
                $permissions[] = $value;
            }
        }

        return $permissions;
    }

    public static function table(Table $table): Table
    {
        return static::getTableProvider()::make($table);
    }

    /**
     * @return class-string|ResourceTable
     */
    public static function getTableProvider(): string|ResourceTable
    {
        return self::$tableProvider
            ??= static::getResourceComponentsNamespace().'\\'.static::getResourceClassShortName().'Table';
    }

    public static function getResourceComponentsNamespace(): string
    {
        return self::RESOURCES_NAMESPACE.self::getResourceClassShortName();
    }

    public static function getResourceClassShortName(): string
    {
        return str_replace('Resource', '', class_basename(static::class));
    }

    public static function form(Form $form): Form
    {
        return static::getFormProvider()::make($form);
    }

    /**
     * @return class-string|ResourceTable
     */
    public static function getFormProvider(): string|ResourceForm
    {
        return self::$formProvider
            ??= static::getResourceComponentsNamespace().'\\'.static::getResourceClassShortName().'Form';
    }

    public static function getPages(): array
    {
        /**
         * @var ListRecords $list
         * @var CreateRole $create
         * @var EditRole $edit
         */
        $list = self::getResourceComponentsNamespace().'\\Pages\\List'.static::getResourceClassShortName().'s';
        $create = self::getResourceComponentsNamespace().'\\Pages\\Create'.static::getResourceClassShortName();
        $edit = self::getResourceComponentsNamespace().'\\Pages\\Edit'.static::getResourceClassShortName();

        return [
            'index' => $list::route('/'),
            'create' => $create::route('/create'),
            'edit' => $edit::route('/{record}/edit'),
        ];
    }
}
