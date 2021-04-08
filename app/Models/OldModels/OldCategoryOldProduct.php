<?php

namespace App\Models\OldModels;


use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


/**
 * App\Models\OldModels\OldCategoryOldProduct
 *
 * @property int $dish_id
 * @property int $category_id
 * @property string|null $excel_column_key
 * @method static Builder|OldCategoryOldProduct newModelQuery()
 * @method static Builder|OldCategoryOldProduct newQuery()
 * @method static Builder|OldCategoryOldProduct query()
 * @method static Builder|OldCategoryOldProduct whereCategoryId($value)
 * @method static Builder|OldCategoryOldProduct whereDishId($value)
 * @method static Builder|OldCategoryOldProduct whereExcelColumnKey($value)
 * @mixin \Eloquent
 */
class OldCategoryOldProduct extends Model
{


    protected $connection = 'mysql-old';
    protected $table = 'jo3aan_dishes_categories';

}
