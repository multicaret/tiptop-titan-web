<?php

namespace App\Models\OldModels;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
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
 * @method static Builder|OldChainTranslation newModelQuery()
 * @method static Builder|OldChainTranslation newQuery()
 * @method static Builder|OldChainTranslation query()
 * @method static Builder|OldChainTranslation whereCoverImage($value)
 * @method static Builder|OldChainTranslation whereDescription($value)
 * @method static Builder|OldChainTranslation whereId($value)
 * @method static Builder|OldChainTranslation whereLocale($value)
 * @method static Builder|OldChainTranslation whereLogo($value)
 * @method static Builder|OldChainTranslation whereRestaurantId($value)
 * @method static Builder|OldChainTranslation whereTitle($value)
 * @mixin Eloquent
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
