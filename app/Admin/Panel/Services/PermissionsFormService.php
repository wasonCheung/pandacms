<?php

declare(strict_types=1);

namespace App\Admin\Panel\Services;

use App\Foundation\Entities\PermissionDO;
use App\Foundation\Enums\DefaultGuard;
use App\Foundation\Models\Role;
use App\Foundation\Services\PermissionService;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class PermissionsFormService
{
    public function __construct(protected PermissionService $service) {}

    /**
     * @return Tabs[]
     */
    public function getComponents(): array
    {
        return collect(DefaultGuard::cases())
            ->map(function (DefaultGuard $guard) {
                return Tabs::make()
                    ->columnSpan('full')
                    ->tabs($this->buildCategories($guard))
                    ->hidden(function (Get $get) use ($guard) {
                        if (DefaultGuard::tryFrom($get('guard_name') ?? '') === $guard) {
                            return false;
                        }

                        return true;
                    });
            })
            ->filter(fn (?Tabs $tabs) => ! empty($tabs->getChildComponents()))
            ->toArray();
    }

    /**
     * @return Tab[]
     */
    protected function buildCategories(DefaultGuard $guard): array
    {
        $categories = $this->service->permissions
            ->filter(fn (PermissionDO $permission) => $permission->guard === $guard)
            ->groupBy(fn (PermissionDO $permission) => $permission->category);

        return collect($categories)
            ->map(function (Collection $permissions) {
                if ($permissions->isEmpty()) {
                    return null;
                }
                /**
                 * @var PermissionDO $first
                 */
                $first = $permissions->first();

                return Tab::make($first->getCategoryLabel())
                    ->schema($this->buildGroup($permissions))
                    ->columns([
                        'sm' => 2,
                        'md' => 4,
                        'lg' => 8,
                    ])
                    ->badge(fn (Tab $component) => $this->resetTabBadge($component))
                    ->afterStateHydrated(fn (Tab $component) => $this->resetTabBadge($component))
                    ->afterStateUpdated(fn (Tab $component) => $this->resetTabBadge($component));
            })
            ->filter(fn (?Tab $tab) => $tab !== null)
            ->toArray();
    }

    /**
     * @return Fieldset[]
     */
    protected function buildGroup(Collection $permissions): array
    {
        return collect($permissions)
            ->map(function (PermissionDO $permission) {
                return Fieldset::make($permission->getGroupLabel())
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 2,
                    ])
                    ->columns(1)
                    ->schema([
                        $this->buildGroupCheckboxList($permission),
                    ]);
            })
            ->toArray();
    }

    protected function buildGroupCheckboxList(PermissionDO $permission): CheckboxList
    {
        return CheckboxList::make('permissions.'.$permission->group)
            ->live()
            ->hiddenLabel()
            ->options($permission->getPermissionsLabel())
            ->afterStateHydrated(fn (
                Component $component,
                ?Model $record
            ) => $this->checkboxListAfterStateHydrated($component, $record, $permission->getPermissionsLabel()))
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
}
