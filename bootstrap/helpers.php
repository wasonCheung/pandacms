<?php

declare(strict_types=1);

use App\Foundation\Entities\TranslationDO;
use App\Foundation\Services\TransClassService;
use App\Foundation\Services\TransModelService;
use Illuminate\Database\Eloquent\Model;

function __class(string $class, ?string $key = null): TranslationDO
{
    return app(TransClassService::class)->trans($class, $key);
}

function __enum(string|UnitEnum $enum): TranslationDO
{
    return app(TransClassService::class)
        ->transEnum($enum);
}

function __model(string|Model $model, string $column, ?string $value = null): TranslationDO
{
    return app(TransModelService::class)->trans($model, $column, $value);
}
