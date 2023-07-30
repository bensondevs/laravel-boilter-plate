<?php

namespace Bensondevs\LaravelBoilerPlate\Enum;

use ReflectionClass;
use ReflectionException;

class Enum
{
    /**
     * Get array that contains list of constants
     *
     * @return array
     */
    public static function getConstants(): array
    {
        $reflectionClass = new ReflectionClass(static::class);
        return $reflectionClass->getConstants();
    }

    /**
     * Get value of enum
     *
     * @param string $value
     * @return string
     * @throws ReflectionException
     */
    public static function getValue(string $value): string
    {
        $constants = static::collectAsArray();
        return $constants[$value];
    }

    /**
     * Get description of enum value
     *
     * @param int|string $value
     * @return string
     * @throws ReflectionException
     */
    public static function getDescription(int|string $value): string
    {
        $constants = static::getConstants();

        return array_search($value, $constants);
    }

    /**
     * Collect all enum values as array
     *
     * @param bool $withDescription
     * @return array<int, int>|array<int, string>
     * @throws ReflectionException
     */
    public static function collectAsArray(bool $withDescription = false): array
    {
        $refClass = new ReflectionClass(static::class);
        $enums = $refClass->getConstants();

        if ($withDescription === true) {
            $tempEnums = [];
            foreach ($enums as $enum) {
                $tempEnums[$enum] = self::getDescription($enum);
            }

            $enums = $tempEnums;
        }

        return $enums;
    }

    /**
     * Get values of the enum
     *
     * @return array
     * @throws ReflectionException
     */
    public static function collectValues(): array
    {
        return array_values(self::getConstants());
    }

    /**
     * Get keys of the enum
     *
     * @return array
     * @throws ReflectionException
     */
    public static function collectKeys(): array
    {
        return array_keys(self::getConstants());
    }

    /**
     * Get values from the enum without excepted items in parameter.
     *
     * @param array|int|string $excludedValues
     * @return array
     * @throws ReflectionException
     */
    public static function collectValuesWithout(array|int|string $excludedValues): array
    {
        $excludedValues = is_array($excludedValues) ?
            $excludedValues :
            [$excludedValues];

        $values = self::collectValues();
        return array_filter($values, function ($value) use ($excludedValues) {
            return !in_array($value, $excludedValues);
        });
    }

    /**
     * Check whether a subscription status existed.
     *
     * @param int|string $value
     * @return bool
     * @throws ReflectionException
     */
    public static function hasValue(int|string $value): bool
    {
        $values = self::collectValues();

        return in_array($value, $values);
    }
}
