<?php

declare(strict_types=1);

namespace App\Foundation;

use App\Foundation\Entities\TranslationDO;
use App\Foundation\Services\PermissionService;
use App\Foundation\Services\TransClassService;
use App\Foundation\Services\TransModelService;
use App\Foundation\Entities\PermissionDO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider;

class FoundationServiceProvider extends ServiceProvider
{
    public array $singletons = [
        TransClassService::class,
        TransModelService::class,
        PermissionService::class,
    ];

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
