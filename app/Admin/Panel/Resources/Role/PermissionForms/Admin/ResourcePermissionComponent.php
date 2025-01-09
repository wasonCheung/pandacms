<?php

declare(strict_types=1);

namespace App\Admin\Panel\Resources\Role\PermissionForms\Admin;

use App\Admin\Services\PanelService;
use App\Foundation\Contracts\HasPermission;
use App\Foundation\Models\Role;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use ReflectionException;

class ResourcePermissionComponent
{
    public function __construct(protected PanelService $panelService) {}

    public function getPermissions(): array
    {
        $resources = $this->getResources();
        $permissions = [];
        foreach ($resources as $resource) {
            try {
                if (in_array(HasPermission::class, class_implements($resource))) {
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
        foreach ($list as $resource => $permissions) {
            $shcema[] = $this->fieldset($resource, $permissions);
        }

        return Tab::make((string) __class(__CLASS__))
            ->schema($shcema ?? [])
            ->columns([
                'xs' => 1,
                'sm' => 2,
                'md' => 4,
                'lg' => 8,
            ])
            ->afterStateHydrated(fn (Tab $component) => $this->resetTabBadge($component))
            ->afterStateUpdated(fn (Tab $component) => $this->resetTabBadge($component));
    }

    protected function fieldset(Resource|string $resource, array $permissions): Fieldset
    {
        return Fieldset::make($resource::getModelLabel())
            ->columnSpan([
                'xs' => 1,
                'sm' => 1,
            ])
            ->columns(1)
            ->schema([
                $this->checkboxList($resource, $permissions),
            ]);
    }

    protected function checkboxList(Resource|string $resource, array $permissions): CheckboxList
    {
        return CheckboxList::make('permissions.'.$resource)
            ->live()
            ->hiddenLabel()
            ->options($permissions)
            ->afterStateHydrated(fn (
                Component $component,
                ?Model $record
            ) => $this->checkboxListAfterStateHydrated($component, $record, $permissions))
            ->bulkToggleable();
    }

    protected function checkboxListAfterStateHydrated(Component $component, ?Role $record, array $permissions): void
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

    protected function resetTabBadge(Tab $component): void
    {
        $selected = $this->getCheckboxSelectedPermissions($component);
        $all = $this->getCheckboxOptionsPermissions($component);
        if ($selected->count() === 0) {
            return;
        }
        $component->badge("{$selected->count()}/{$all->count()}");
    }

    protected function getCheckboxOptionsPermissions(Tab $component): Collection
    {
        $result = collect();
        collect($component->getContainer()->getFlatComponents())->each(function (Component $component) use ($result) {
            if ($component instanceof CheckboxList) {
                $result->push(...$component->getOptions());
            }
        });

        return $result;
    }

    protected function getCheckboxSelectedPermissions(Tab $component): Collection
    {
        $result = collect();
        collect($component->getContainer()->getFlatComponents())->each(function (Component $component) use ($result) {
            if ($component instanceof CheckboxList) {
                $result->push(...$component->getState());
            }
        });

        return $result;
    }
}
