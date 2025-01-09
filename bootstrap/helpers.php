<?php
declare(strict_types=1);

use App\Foundation\Entities\TransDO;
use App\Foundation\Services\TransClassService;
use App\Foundation\Services\TransModelService;
use Illuminate\Database\Eloquent\Model;

function __class(string $class, ?string $key = null): TransDO
{
    return app(TransClassService::class)->trans($class, $key);
}

function __enum(string|UnitEnum $enum): TransDO
{
    return app(TransClassService::class)
        ->transEnum($enum);
}

function __model(string|Model $model, string $column, ?string $value = null): TransDO
{
    return app(TransModelService::class)->trans($model, $column, $value);
}
