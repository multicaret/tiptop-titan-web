<?php


namespace App\Contracts;


interface ShouldHaveTypes
{
    /**
     * @return array
     */
    public static function getTypesArray(): array;

    public static function getCorrectType(string $type = null): int;

    public static function getCorrectTypeName($type = null, $isLocalized = true): ?string;
}
