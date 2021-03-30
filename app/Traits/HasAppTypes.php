<?php

namespace App\Traits;

trait HasAppTypes
{
    public static function requestTypeIsGrocery(): bool
    {
        return request()->type === self::getTypesArray()[self::TYPE_GROCERY_OBJECT];
    }

    public static function requestTypeIsFood(): bool
    {
        return request()->type === self::getTypesArray()[self::TYPE_FOOD_OBJECT];
    }

    public static function isFood(): bool
    {
        return request()->type === self::getTypesArray()[self::TYPE_FOOD_OBJECT];
    }

    public static function isGrocery(): bool
    {
        return request()->type === self::getTypesArray()[self::TYPE_GROCERY_OBJECT];
    }

    /*
    public static function checkRequestTypes($model): object
    {
        return new class {
            public static function isFood(): bool
            {
                return request()->type === $model::getTypesArray()[self::TYPE_FOOD_OBJECT];
            }

            public static function isGrocery(): bool
            {
                return request()->type === $model::getTypesArray()[self::TYPE_GROCERY_OBJECT];
            }
        };
    }*/

    public static function getTypesArray(): array
    {
        $model = (last(explode('\\', \Str::lower(get_class()))));
        return [
            self::TYPE_GROCERY_OBJECT => "grocery-$model",
            self::TYPE_FOOD_OBJECT => "food-$model",
        ];
    }
}
