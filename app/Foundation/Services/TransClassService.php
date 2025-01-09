<?php

declare(strict_types=1);

namespace App\Foundation\Services;

use App\Foundation\Entities\TransDO;
use UnitEnum;

class TransClassService
{
    public const GROUP_MAPPINGS = [
        'admin' => 'App\\Admin',
        'portal' => 'App\\Portal',
        'foundation' => 'App\\Foundation',
    ];

    public const DEFAULT_GROUP = 'messages';

    private array $cachedKeys = [];

    public function trans(string $class, ?string $key = null): TransDO
    {
        $classKey = $this->parseKey($class);

        return TransDO::make($key ? "$classKey.$key" : $classKey);
    }

    public function transEnum(string|UnitEnum $enum): TransDO
    {
        $do = TransDO::make($this->parseEnumKey($enum));
        if ($enum instanceof UnitEnum) {
            return $do->notFound($enum->name);
        }

        return $do;
    }

    public function parseEnumKey(string|UnitEnum $enum): string
    {
        if ($enum instanceof UnitEnum) {
            return self::parseKey($enum::class).'.'.$enum->name;
        }

        return self::parseKey($enum);
    }

    public function parseKey(string $class): string
    {
        return self::parseCachedKey($class, function () use ($class) {
            return self::parseClassGroupName($class).'.'.$class;
        });
    }

    private function parseCachedKey(string $key, callable $callback): string
    {
        return $this->cachedKeys[$key] ??= $callback();
    }

    public function parseClassGroupName(string $class): string
    {
        foreach (self::GROUP_MAPPINGS as $group => $namespacePrefix) {
            if (str_starts_with($class, $namespacePrefix)) {
                return $group;
            }
        }

        return self::DEFAULT_GROUP;
    }
}
