<?php

namespace App\Models\OldModels;



use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OldModels\OldProductTranslation
 *
 * @property int $id
 * @property int|null $dish_id
 * @property string|null $locale
 * @property string $title
 * @property string|null $description
 * @property string|null $image
 * @property string|null $unit
 * @method static \Illuminate\Database\Eloquent\Builder|OldProductTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OldProductTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OldProductTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|OldProductTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldProductTranslation whereDishId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldProductTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldProductTranslation whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldProductTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldProductTranslation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldProductTranslation whereUnit($value)
 * @mixin \Eloquent
 */
class OldProductTranslation extends Model
{
    protected $connection = 'mysql-old';
    protected $table = 'jo3aan_dishes_translations';
    protected $primaryKey = 'id';

    public static function attributesComparing(): array
    {
        return [
            'title' => 'title',
            'description' => 'description',
            'locale' => 'locale',
            'dish_id' => 'product_id',
        ];
    }


}
