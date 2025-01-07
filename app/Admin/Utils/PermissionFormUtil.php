<?php

declare(strict_types=1);

namespace App\Admin\Utils;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use ReflectionException;

class PermissionFormUtil
{
    public static function getFormComponent(): Component
    {
        return Tabs::make('Permissions')
            ->columnSpan('full')
            ->contained()
            ->tabs([
                static::buildResoucesForm(),
            ]);
    }

    public static function buildResoucesForm(): Tab
    {
        $list = static::collectResourcesPermissions();
        $shcema = [];
        /**
         * @var resource $resource
         */
        $count = 0;
        foreach ($list as $resource => $permissions) {
            $count = count($permissions) + $count;
            $shcema[] = Fieldset::make()
                ->label($resource::getModelLabel())
                ->schema([
                    CheckboxList::make('permissions@'.class_basename($resource))
                        ->hiddenLabel()
                        ->afterStateHydrated(function (Component $component, ?Model $record) use ($permissions
                        ) {
                            if (!$record) {
                                return;
                            }
                            $has = collect($permissions)
                                ->filter(function ($label, $permissionKey) use ($record) {
                                    return $record->checkPermissionTo($permissionKey);
                                })
                                ->keys()
                                ->toArray();
                            $component->state($has);
                        })
                        ->bulkToggleable()
                        ->columns([
                            'sm' => 2,
                            'md' => 3,
                            'lg' => 4,
                            'xl' => 6,
                        ])
                        ->gridDirection('row')
                        ->options($permissions),
                ])
            ;
        }

        return Tab::make(__('admin.services.admin_form_builder.resources'))
            ->badge($count)
            ->schema($shcema)
        ;
    }

    public static function collectResourcesPermissions(): array
    {
        $resources = app('admin.panel')->getResources();
        $permissions = [];
        foreach ($resources as $resource) {
            $reflector = new ReflectionClass($resource);
            try {
                if ($reflector->getMethod('definedPermissions')->isStatic()) {
                    $permissions[$resource] = $resource::definedPermissions();
                }
            } catch (ReflectionException) {
                continue;
            }
        }

        return $permissions;
    }
}
