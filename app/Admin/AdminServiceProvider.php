<?php

declare(strict_types=1);

namespace App\Admin;

use App\Admin\Exceptions\LivewireHook;
use App\Admin\Services\PanelService;
use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Support\ServiceProvider;
use Livewire\ComponentHookRegistry;

class AdminServiceProvider extends ServiceProvider
{
    public array $singletons = [
        PanelService::class,
    ];

    public function register(): void
    {
        $this->registerPanelService();
    }

    /** @noinspection PhpParamsInspection */
    private function registerPanelService(): void
    {

        Filament::registerPanel(fn (): Panel => $this->app->make(PanelService::class)->panel);
    }

    public function boot(): void
    {
        $this->bootLivewireHooks();
    }

    private function bootLivewireHooks(): void
    {
        ComponentHookRegistry::register(LivewireHook::class);
    }
}
