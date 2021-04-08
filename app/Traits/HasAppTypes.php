<?php

namespace App\Traits;

use Str;

trait HasAppTypes
{
    public static function isFood(): bool
    {
        return request()->type === self::getChannelsArray()[self::CHANNEL_FOOD_OBJECT];
    }

    public static function isGrocery(): bool
    {
        return request()->type === self::getChannelsArray()[self::CHANNEL_GROCERY_OBJECT];
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeGroceries($query)
    {
        return $query->where('type', self::CHANNEL_GROCERY_OBJECT);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeFoods($query)
    {
        return $query->where('type', self::CHANNEL_FOOD_OBJECT);
    }


    /*
    public static function checkRequestTypes($model): object
    {
        return new class {
            public static function isFood(): bool
            {
                return request()->type === $model::getChannelsArray()[self::CHANNEL_FOOD_OBJECT];
            }

            public static function isGrocery(): bool
            {
                return request()->type === $model::getChannelsArray()[self::CHANNEL_GROCERY_OBJECT];
            }
        };
    }*/

    public static function getCorrectChannelName($channel = null, $isLocalized = true): ?string
    {
        if ( ! is_null($channel) && ! is_numeric($channel)) {
            $channel = self::getCorrectChannel($channel);
        }
        if (array_key_exists($channel, self::getChannelsArray())) {
            return $isLocalized ? self::getChannelsArray()[$channel] : self::getChannelsArray()[$channel];
        }

        return null;
    }


    public static function getChannelsArray(): array
    {
        $model = (last(explode('\\', Str::lower(get_class()))));

        return [
            self::CHANNEL_GROCERY_OBJECT => "grocery-$model",
            self::CHANNEL_FOOD_OBJECT => "food-$model",
        ];
    }

    public static function getCorrectChannel(string $type = null): int
    {
        return array_search($type, self::getChannelsArray());
    }
}
