<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RemoteTranslation
 *
 * @property int $id
 * @property int $boot_id
 * @property string|null $title
 * @property array|null $data_translated
 * @property string $locale
 * @method static Builder|RemoteTranslation newModelQuery()
 * @method static Builder|RemoteTranslation newQuery()
 * @method static Builder|RemoteTranslation query()
 * @method static Builder|RemoteTranslation whereBootId($value)
 * @method static Builder|RemoteTranslation whereDataTranslated($value)
 * @method static Builder|RemoteTranslation whereId($value)
 * @method static Builder|RemoteTranslation whereLocale($value)
 * @method static Builder|RemoteTranslation whereTitle($value)
 * @mixin Eloquent
 */
class RemoteTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['title', 'data_translated'];

    protected $casts = [
        'data_translated' => 'json',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (RemoteTranslation $bootTranslation) {
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
