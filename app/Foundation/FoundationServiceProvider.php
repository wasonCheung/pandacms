<?php

declare(strict_types=1);

namespace App\Foundation;

use App\Foundation\Entities\TranslationDO;
use App\Foundation\Services\AvatarService;
use App\Foundation\Services\ClassTranslator;
use App\Foundation\Services\ModelTranslator;
use App\Foundation\Services\PermissionRegistry;
use App\Foundation\Services\RoleService;
use App\Foundation\Services\UserService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider;

class FoundationServiceProvider extends ServiceProvider
{
    public array $singletons = [
        ClassTranslator::class,
        ModelTranslator::class,
        PermissionRegistry::class,
        AvatarService::class,
        RoleService::class,
        UserService::class,
    ];

    public function register(): void
    {
        $this->registerAvatarDefaultProvider();
    }

    private function registerAvatarDefaultProvider(): void
    {
        $this->app->singleton(app(AvatarService::class)->getDefaultAvatarProvider());
    }

    public function boot(): void
    {
        $this->bootFactoryHandlers();
        $this->bootMarcos();
    }

    private function bootFactoryHandlers(): void
    {
        if ($this->app->runningInConsole() === false) {
            return;
        }
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Database\\Factories\\'.class_basename($modelName).'Factory';
        });
        Factory::guessModelNamesUsing(function (Factory $factory) {
            return 'App\\Foundation\\Models\\'.str_replace('Factory', '', class_basename($factory));
        });
    }

    private function bootMarcos(): void
    {
        Builder::macro('getTranslation',
            function (string $column): TranslationDO {
                return __model($this->getModel(), $column);
            });
    }
}
