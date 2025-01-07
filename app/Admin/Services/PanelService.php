<?php

declare(strict_types=1);

namespace App\Admin\Services;

use App\Admin\Panel\Pages\LoginPage;
use App\Foundation\Enums\DefaultGuard;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Illuminate\Config\Repository;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class PanelService
{
    public const ID = 'admin';

    public const GUARD = DefaultGuard::Admin->value;

    public function __construct(public Panel $panel, protected Repository $config)
    {
        $this->panel = $panel
            ->default()
            ->id(self::ID)
            ->authGuard(self::GUARD)
            ->spa()
            ->unsavedChangesAlerts()
            ->databaseTransactions()
            ->login(LoginPage::class)
            ->path($this->getPath())
            ->colors($this->getColors())
            ->authMiddleware($this->getAuthMiddlewares())
            ->middleware($this->getMiddlewares())
            ->discoverResources(
                app_path('Admin/Panel/Resources'),
                'App\\Admin\\Panel\\Resources'
            )
            ->discoverPages(
                app_path('Admin/Panel/Pages'),
                'App\\Admin\\Panel\\Pages'
            )
            ->discoverWidgets(
                app_path('Admin/Panel/Widgets'),
                'App\\Admin\\Panel\\Widgets'
            );
    }

    public function getAuthMiddlewares(): array
    {
        return $this->config->get('pandacms.admin.panel.auth_middlewares', [
            Authenticate::class,
        ]);
    }

    public function getPath(): string
    {
        return $this->config->get('pandacms.admin.panel.path', 'admin');
    }

    public function getColors(): array
    {
        return $this->config->get('pandacms.admin.panel.colors', [
            'primary' => '#4c51bf',
        ]);
    }

    public function getMiddlewares(): array
    {
        return $this->config->get('pandacms.admin.panel.middlewares', [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            DisableBladeIconComponents::class,
            DispatchServingFilamentEvent::class,
        ]);
    }
}
