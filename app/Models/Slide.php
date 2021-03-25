<?php

namespace App\Models;

use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use App\Traits\HasUuid;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Slide extends Model
{
    use SoftDeletes,
        HasUuid,
        Translatable,
        HasStatuses;

    const STATUS_INCOMPLETE = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_INACTIVE = 3;

    const TYPE_EXTERNAL = 1;
    const TYPE_UNIVERSAL = 2;
    const TYPE_DEFERRED_DEEPLINK = 3;
    const TYPE_DEEPLINK = 4;

    protected $with = ['translations'];

    protected $translatedAttributes = ['alt_tag','image'];

    protected $fillable = ['title', 'description', 'link_value', 'link_type', 'begins_at', 'expires_at', 'status'];

    protected $casts = [
        'begins_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public static function getTypesArray(): array
    {
        return [
            self::TYPE_EXTERNAL => 'external',
            self::TYPE_UNIVERSAL => 'universal',
            self::TYPE_DEFERRED_DEEPLINK => 'deferred-deeplink',
            self::TYPE_DEEPLINK => 'deeplink',
        ];
    }
}
