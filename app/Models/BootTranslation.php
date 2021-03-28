<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BootTranslation
 *
 * @property int $id
 * @property int $boot_id
 * @property string|null $title
 * @property array|null $data_translated
 * @property string $locale
 * @method static \Illuminate\Database\Eloquent\Builder|BootTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BootTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BootTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|BootTranslation whereBootId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BootTranslation whereDataTranslated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BootTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BootTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BootTranslation whereTitle($value)
 * @mixin \Eloquent
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
