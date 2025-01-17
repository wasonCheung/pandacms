<?php

declare(strict_types=1);

namespace App\Foundation\Services;

use App\Foundation\Entities\TranslationDO;
use UnitEnum;

class ClassTranslator
{
    private static array $mappings = [
        'admin' => 'App\\Admin',
        'portal' => 'App\\Portal',
        'foundation' => 'App\\Foundation',
    ];

    public const DEFAULT_GROUP = 'messages';

    private array $cachedKeys = [];

    public function trans(string $class, ?string $key = null): TranslationDO
    {
        $classKey = $this->parseKey($class);

        return TranslationDO::make($key ? "$classKey.$key" : $classKey);
    }

    public function transEnum(string|UnitEnum $enum): TranslationDO
    {
        $do = TranslationDO::make($this->parseEnumKey($enum));
        if ($enum instanceof UnitEnum) {
            return $do->fallback($enum->name);
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
        foreach (static::getMappings() as $group => $namespacePrefix) {
            if (str_starts_with($class, $namespacePrefix)) {
                return $group;
            }
        }

        return self::DEFAULT_GROUP;
    }

    public function addMapping(string $group, string $namespacePrefix): void
    {
        self::$mappings[$group] = $namespacePrefix;
    }

    public static function getMappings(): array
    {
        return self::$mappings;
    }
}
