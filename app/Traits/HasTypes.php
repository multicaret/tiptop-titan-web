<?php

namespace App\Traits;

trait HasTypes
{

    /**
     * @param  string  $type
     *
     * @return int
     */
    public static function getCorrectType(string $type = null): int
    {
        return array_search($type, self::getTypesArray());
    }

    /**
     * @param  null  $type
     *
     * @param  bool  $isLocalized
     *
     * @return string|null
     */
    public static function getCorrectTypeName($type = null, $isLocalized = true): ?string
    {
        if ( ! is_null($type) && ! is_numeric($type)) {
            $type = self::getCorrectType($type);
        }
        if (array_key_exists($type, self::getTypesArray())) {
            return $isLocalized ? trans('strings.'.self::getTypesArray()[$type]) : self::getTypesArray()[$type];
        }

        return null;
    }
}
