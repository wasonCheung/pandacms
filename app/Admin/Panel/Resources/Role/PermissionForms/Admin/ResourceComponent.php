<?php

declare(strict_types=1);

namespace App\Admin\Panel\Resources\Role\PermissionForms\Admin;

use App\Admin\Services\PanelService;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use ReflectionException;

class ResourceComponent
{
    public const DEFINED_PERMISSIONS_METHOD = 'definedPermissions';

    public function __construct(protected PanelService $panelService) {}

    public function getPermissions(): array
    {
        $resources = $this->getResources();
        $permissions = [];
        foreach ($resources as $resource) {
            try {
                $reflector = new ReflectionClass($resource);
                if ($reflector->getMethod(self::DEFINED_PERMISSIONS_METHOD)->isStatic()) {
                    $permissions[$resource] = $resource::definedPermissions();
                }
            } catch (ReflectionException) {
                continue;
            }
        }

        return $permissions;
    }

    public function getResources(): array
    {
        return $this->panelService->panel->getResources();
    }

    public function getComponent(): Tab
    {
        $list = $this->getPermissions();
        $count = 0;
        foreach ($list as $resource => $permissions) {
            $count = count($permissions) + $count;
            $shcema[] = $this->buildFieldset($resource, $permissions);
        }

        return Tab::make((string) __class(__CLASS__))
            ->badge($count)
            ->schema($shcema ?? []);
    }

    protected function buildFieldset(Resource|string $resource, array $permissions): Fieldset
    {
        return Fieldset::make()
            ->label($resource::getModelLabel())
            ->schema([
                $this->buildCheckboxList($resource, $permissions),
            ]);
    }

    protected function buildCheckboxList(Resource|string $resource, array $permissions): CheckboxList
    {
        return CheckboxList::make('permissions@'.class_basename($resource))
            ->hiddenLabel()
            ->afterStateHydrated(fn (
                Component $component,
                ?Model $record
            ) => $this->checkboxListAfterStateHydrated($component, $record, $permissions))
            ->bulkToggleable()
            ->columns([
                'sm' => 2,
                'md' => 3,
                'lg' => 4,
                'xl' => 6,
            ])
            ->gridDirection('row')
            ->options($permissions);
    }

    public function checkboxListAfterStateHydrated(Component $component, ?Model $record, array $permissions): void
    {
        if (! $record) {
            return;
        }
        $has = collect($permissions)
            ->filter(function ($label, $permissionKey) use ($record) {
                return $record->checkPermissionTo($permissionKey);
            })
            ->keys()
            ->toArray();
        $component->state($has);
    }
}
