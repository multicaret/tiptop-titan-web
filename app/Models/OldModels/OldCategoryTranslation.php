<?php

namespace App\Models\OldModels;


use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\OldModels\OldCategoryTranslation
 *
 * @property int $id
 * @property int $category_id
 * @property string $locale
 * @property string $title
 * @property string|null $description
 * @property string|null $icon
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OldCategoryTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OldCategoryTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OldCategoryTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|OldCategoryTranslation whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldCategoryTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldCategoryTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldCategoryTranslation whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldCategoryTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldCategoryTranslation whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldCategoryTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldCategoryTranslation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldCategoryTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OldCategoryTranslation extends Model
{


    protected $connection = 'mysql-old';
    protected $table = 'cms_categories_translations';
    protected $primaryKey = 'id';

    public static function attributesComparing(): array
    {
        return [
            'id' => 'id',
            'title' => 'title',
            'description' => 'description',
            'locale' => 'locale',
            'category_id' => 'taxonomy_id',
        ];
    }


}
