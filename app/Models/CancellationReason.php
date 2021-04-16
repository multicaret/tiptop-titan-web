<?php

namespace App\Models;

use App\Traits\HasStatuses;
use App\Traits\HasTypes;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class CancellationReason extends Model
{
    use Translatable;
    use HasTypes;
    use HasStatuses;

    public const TYPE_BY_CUSTOMER = 1;
    public const TYPE_BY_RESTAURANT = 2;
    public const TYPE_BY_ADMIN = 3;

    public const STATUS_NOT_DELIVERED = 1;
    public const STATUS_DECLINED = 2;
    public const STATUS_CANCELED = 3;

    protected $with = ['translations'];
    protected array $translatedAttributes = ['reason', 'description'];

    public static function getTypesArray(): array
    {
        return [
            self::TYPE_BY_CUSTOMER => 'by-customer',
            self::TYPE_BY_RESTAURANT => 'by-restaurant',
            self::TYPE_BY_ADMIN => 'by-admin',
        ];
    }
    public static function getStatusesArray(): array
    {
        return [
            self::STATUS_NOT_DELIVERED => 'not-delivered',
            self::STATUS_DECLINED => 'declined',
            self::STATUS_CANCELED => 'canceled',
        ];
    }

    public static function getAllStatusesRich(): array
    {
        return [
            self::STATUS_NOT_DELIVERED => [
                'id' => self::STATUS_NOT_DELIVERED,
                'title' => trans('strings.not_delivered'),
                'class' => 'dark',
            ],
            self::STATUS_DECLINED => [
                'id' => self::STATUS_DECLINED,
                'title' => trans('strings.declined'),
                'class' => 'light',
            ],
            self::STATUS_CANCELED => [
                'id' => self::STATUS_CANCELED,
                'title' => trans('strings.canceled'),
                'class' => 'danger',
            ],
        ];
    }

}
