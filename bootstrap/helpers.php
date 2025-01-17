<?php

declare(strict_types=1);

use App\Foundation\Entities\TranslationDO;
use App\Foundation\Services\ClassTranslator;
use App\Foundation\Services\ModelTranslator;
use Illuminate\Database\Eloquent\Model;

function __class(string $class, ?string $key = null): TranslationDO
{
    return app(ClassTranslator::class)->trans($class, $key);
}

function __enum(string|UnitEnum $enum): TranslationDO
{
    return app(ClassTranslator::class)
        ->transEnum($enum);
}

function __model(string|Model $model, string $column, ?string $value = null): TranslationDO
{
    return app(ModelTranslator::class)->trans($model, $column, $value);
}
