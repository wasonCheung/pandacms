<?php

declare(strict_types=1);

namespace App\Admin\Panel\Resources\Role\PermissionForms;

use App\Admin\Panel\Resources\Role\PermissionForms\Admin\ResourceComponent;
use Filament\Forms\Components\Tabs;
use Filament\Resources\Components\Tab;

class AdminPermissionForm
{
    public const FIELD_NAME = 'permissions';

    public const DEFAULT_COMPONENTS = [
        ResourceComponent::class,
    ];

    protected array $componets = [];

    public function __construct()
    {
        $this->componets = collect(self::DEFAULT_COMPONENTS)
            ->map(fn (string $component) => app($component)->getComponent())->toArray();
    }

    public function getComponent(): Tabs
    {
        return Tabs::make(self::FIELD_NAME)
            ->columnSpan('full')
            ->contained()
            ->tabs($this->componets);
    }

    public function addComponent(Tab $component): void
    {
        $this->componets[] = $component;
    }
}
