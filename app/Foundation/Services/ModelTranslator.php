<?php

declare(strict_types=1);

namespace App\Foundation\Services;

use App\Foundation\Entities\TranslationDO;
use Illuminate\Database\Eloquent\Model;

class ModelTranslator
{
    public const DEFAULT_GROUP = 'models';

    private array $cachedKeys = [];

    public function trans(string|Model $model, string $column, ?string $value = null): TranslationDO
    {

        $class = $model instanceof Model ? $model::class : $model;

        $value = $model instanceof Model ? $model->{$column} : $value;

        $key = $this->parseKey($class).".$column";

        if ($value) {
            return TranslationDO::make($key.'.'.$value)
                ->fallback($value);
        }

        return TranslationDO::make($key);
    }

    public function parseKey(string $model): string
    {
        return $this->parseCachedKey($model, function () use ($model) {
            return self::DEFAULT_GROUP.'.'.strtolower(class_basename($model));
        });
    }

    private function parseCachedKey(string $key, callable $callback): string
    {
        return $this->cachedKeys[$key] ??= $callback();
    }
}
