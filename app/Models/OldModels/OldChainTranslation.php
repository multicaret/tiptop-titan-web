<?php

namespace App\Models\OldModels;


use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\OldModels\OldChainTranslation
 *
 * @property int $id
 * @property int $restaurant_id
 * @property string $locale
 * @property string $title
 * @property string|null $description
 * @property string|null $logo
 * @property string|null $cover_image
 * @method static \Illuminate\Database\Eloquent\Builder|OldChainTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OldChainTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OldChainTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|OldChainTranslation whereCoverImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldChainTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldChainTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldChainTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldChainTranslation whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldChainTranslation whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldChainTranslation whereTitle($value)
 * @mixin \Eloquent
 */
class OldChainTranslation extends Model
{
    protected $connection = 'mysql-old';
    protected $table = 'jo3aan_restaurants_translations';


    public static function attributesComparing(): array
    {
        return [
            'title' => 'title',
            'description' => 'description',
            'locale' => 'locale',
            'restaurant_id' => 'chain_id',
        ];
    }
}
