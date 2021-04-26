<?php

namespace App\Models\OldModels;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|OldCategoryTranslation newModelQuery()
 * @method static Builder|OldCategoryTranslation newQuery()
 * @method static Builder|OldCategoryTranslation query()
 * @method static Builder|OldCategoryTranslation whereCategoryId($value)
 * @method static Builder|OldCategoryTranslation whereCreatedAt($value)
 * @method static Builder|OldCategoryTranslation whereDescription($value)
 * @method static Builder|OldCategoryTranslation whereIcon($value)
 * @method static Builder|OldCategoryTranslation whereId($value)
 * @method static Builder|OldCategoryTranslation whereImage($value)
 * @method static Builder|OldCategoryTranslation whereLocale($value)
 * @method static Builder|OldCategoryTranslation whereTitle($value)
 * @method static Builder|OldCategoryTranslation whereUpdatedAt($value)
 * @mixin Eloquent
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
