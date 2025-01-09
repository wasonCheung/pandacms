<?php

declare(strict_types=1);

namespace App\Admin\Panel\Resources\Role\PermissionForms;

use App\Admin\Panel\Resources\Role\PermissionForms\Admin\ResourcePermissionComponent;
use Filament\Forms\Components\Tabs;
use Filament\Resources\Components\Tab;

class AdminForm
{
    public const DEFAULT_COMPONENTS = [
        ResourcePermissionComponent::class,
    ];

    protected array $componets = [];

    public function __construct()
    {
        $this->componets = collect(self::DEFAULT_COMPONENTS)
            ->map(fn (string $component) => app($component)->getComponent())->toArray();
    }

    public function getComponent(): Tabs
    {
        return Tabs::make()
            ->columnSpan('full')
            ->tabs($this->componets);
    }

    public function addComponent(Tab $component): void
    {
        $this->componets[] = $component;
    }
}
