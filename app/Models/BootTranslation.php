<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BootTranslation
 *
 * @property int $id
 * @property int $boot_id
 * @property string|null $title
 * @property array|null $data_translated
 * @property string $locale
 * @method static Builder|BootTranslation newModelQuery()
 * @method static Builder|BootTranslation newQuery()
 * @method static Builder|BootTranslation query()
 * @method static Builder|BootTranslation whereBootId($value)
 * @method static Builder|BootTranslation whereDataTranslated($value)
 * @method static Builder|BootTranslation whereId($value)
 * @method static Builder|BootTranslation whereLocale($value)
 * @method static Builder|BootTranslation whereTitle($value)
 * @mixin Eloquent
 */
class BootTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['title', 'data_translated'];

    protected $casts = [
        'data_translated' => 'json',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (BootTranslation $bootTranslation) {
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
