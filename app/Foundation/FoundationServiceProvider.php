<?php

declare(strict_types=1);

namespace App\Foundation;

use App\Foundation\Entities\ModelTranslationDO;
use App\Foundation\Services\ModelTranslationService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\Translator;

class FoundationServiceProvider extends ServiceProvider
{
    public array $singletons = [
        ModelTranslationService::class,
    ];

    public function boot(): void
    {
        $this->bootModelFactoryHandlers();
        $this->bootTranslatorMarcos();
    }

    private function bootTranslatorMarcos(): void
    {
        Translator::macro('model',
            function (Model|string $model, string $column, ?string $value = null): ModelTranslationDO {
                return app(ModelTranslationService::class)->resolve($model, $column, $value);
            });
    }

    private function bootModelFactoryHandlers(): void
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
