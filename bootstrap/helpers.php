<?php
declare(strict_types=1);

use App\Foundation\Entities\TransDO;
use App\Foundation\Utils\TransUtil;
use Illuminate\Database\Eloquent\Model;

function __class(string $class, ?string $key = null): TransDO
{
    return TransUtil::getClass($class, $key);
}

function __enum(string|UnitEnum $enum): TransDO
{
    return TransUtil::getEnum($enum);
}

function __model(string|Model $model, string $column, ?string $value = null): TransDO
{
    return TransUtil::getModel($model, $column, $value);
}
