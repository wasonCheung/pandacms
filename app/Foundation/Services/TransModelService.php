<?php

declare(strict_types=1);

namespace App\Foundation\Services;

use App\Foundation\Entities\TransDO;
use Illuminate\Database\Eloquent\Model;

class TransModelService
{
    public const DEFAULT_GROUP = 'models';

    private array $cachedKeys = [];

    public function trans(string|Model $model, string $column, ?string $value = null): TransDO
    {

        $class = $model instanceof Model ? $model::class : $model;

        $value = $model instanceof Model ? $model->{$column} : $value;

        $key = $this->parseKey($class).".$column";

        if ($value) {
            return TransDO::make($key.'.'.$value)
                ->notFound($value);
        }

        return TransDO::make($key);
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
