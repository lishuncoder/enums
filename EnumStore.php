<?php
/**
 * @author LiShun
 * Date: 2023/04/28
 */

namespace Lishun\Enums\Utils;

class EnumStore
{
    protected static mixed $store = [];

    public static function set(string $class,  string|int $name, $value): bool
    {
        self::$store[$class][$name] = $value;

        return true;
    }

    public static function isset(string $class): bool
    {
        return isset(self::$store[$class]);
    }

    public static function get(string $class): array
    {
        return self::$store[$class] ?? [];
    }
}
