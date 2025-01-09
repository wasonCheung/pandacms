<?php

declare(strict_types=1);

namespace App\Foundation;

use App\Foundation\Entities\TransDO;
use App\Foundation\Services\TransClassService;
use App\Foundation\Services\TransModelService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider;

class FoundationServiceProvider extends ServiceProvider
{
    public array $singletons = [
        TransClassService::class,
        TransModelService::class,
    ];

    public function boot(): void
    {
        $this->bootFactoryHandlers();
        $this->bootMarcos();
    }

    private function bootMarcos(): void
    {
        Builder::macro('getTranslation',
            function (string $column): TransDO {
                return __model($this->getModel(), $column);
            });
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
}
