<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RemoteConfigTranslation
 *
 * @property int $id
 * @property int $boot_id
 * @property string|null $title
 * @property array|null $data_translated
 * @property string $locale
 * @method static Builder|RemoteConfigTranslation newModelQuery()
 * @method static Builder|RemoteConfigTranslation newQuery()
 * @method static Builder|RemoteConfigTranslation query()
 * @method static Builder|RemoteConfigTranslation whereBootId($value)
 * @method static Builder|RemoteConfigTranslation whereDataTranslated($value)
 * @method static Builder|RemoteConfigTranslation whereId($value)
 * @method static Builder|RemoteConfigTranslation whereLocale($value)
 * @method static Builder|RemoteConfigTranslation whereTitle($value)
 * @mixin Eloquent
 */
class RemoteConfigTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['title', 'data_translated'];

    protected $casts = [
        'data_translated' => 'json',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (RemoteConfigTranslation $bootTranslation) {
            $dataTranslated = [
                'alert' => [
                    'title' => '',
                    'message' => '',
                ],
                'foo' => 'bar',
            ];
            $bootTranslation->data_translated = $dataTranslated;
        });
    }

}
