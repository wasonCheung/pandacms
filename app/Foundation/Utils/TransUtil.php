<?php

declare(strict_types=1);

namespace App\Foundation\Utils;

use App\Foundation\Entities\TransDO;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class TransUtil
{
    public const CLASS_GROUP_MAPPINGS = [
        'admin' => 'App\\Admin',
        'portal' => 'App\\Portal',
        'foundation' => 'App\\Foundation',
    ];

    public const DEFAULT_CLASS_GROUP = 'messages';

    public const DEFAULT_MODEL_GROUP = 'models';

    private static array $parseClassKeyCache = [];

    private static array $parseModelKeyCache = [];

    public static function getClass(string $class, ?string $key = null): TransDO
    {
        $classKey = self::parseClassKey($class);

        return TransDO::make($key ? "$classKey.$key" : $classKey);
    }

    public static function getEnum(string|UnitEnum $enum): TransDO
    {
        $do = TransDO::make(self::parseEnumKey($enum));
        if ($enum instanceof UnitEnum) {
            return $do->notFound($enum->name);
        }

        return $do;
    }

    public static function getModel(string|Model $model, string $column, ?string $value = null): TransDO
    {
        if ($model instanceof Model) {
            $value = $model->{$column};
            $class = $model::class;
        } else {
            $class = $model;
        }

        $key = self::parseModelKey($class).".$column";

        if ($value) {
            return TransDO::make($key.'.'.$value)
                ->notFound($value);
        }

        return TransDO::make($key);
    }

    public static function parseClassGroupName(string $class): string
    {
        foreach (self::CLASS_GROUP_MAPPINGS as $group => $namespacePrefix) {
            if (str_starts_with($class, $namespacePrefix)) {
                return $group;
            }
        }

        return self::DEFAULT_CLASS_GROUP;
    }

    public static function parseClassKey(string $class): string
    {
        if (isset(self::$parseClassKeyCache[$class])) {
            return self::$parseClassKeyCache[$class];
        }

        return self::$parseClassKeyCache[$class] = self::parseClassGroupName($class).'.'.$class;
    }

    public static function parseEnumKey(string|UnitEnum $enum): string
    {
        if ($enum instanceof UnitEnum) {
            return self::parseClassKey(get_class($enum)).'.'.$enum->name;
        }

        return self::parseClassKey($enum);
    }

    public static function parseModelName(string $model): string
    {
        return strtolower(class_basename($model));
    }

    public static function parseModelKey(string $model)
    {
        if (isset(self::$parseModelKeyCache[$model]) === false) {
            self::$parseModelKeyCache[$model] = self::DEFAULT_MODEL_GROUP.'.'.self::parseModelName($model);
        }

        return self::$parseModelKeyCache[$model];
    }
}
