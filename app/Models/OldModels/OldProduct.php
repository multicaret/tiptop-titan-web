<?php

namespace App\Models\OldModels;


use Astrotomic\Translatable\Translatable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;


/**
 * App\Models\OldModels\OldProduct
 *
 * @property int $id
 * @property int $restaurant_id
 * @property string $type
 * @property string $status
 * @property string|null $excel_id
 * @property int|null $sort_order
 * @property int|null $calories
 * @property int|null $carbs
 * @property int|null $fats
 * @property int|null $prots
 * @property int|null $estimated_time_to_prepare
 * @property string $price
 * @property string $discount_type
 * @property string $discount
 * @property string|null $discount_deadline
 * @property string $rating
 * @property int $rating_count
 * @property int|null $added_by
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|\App\Models\OldModels\OldCategory[] $categories
 * @property-read int|null $categories_count
 * @property-read \App\Models\OldModels\OldProductTranslation|null $translation
 * @property-read Collection|\App\Models\OldModels\OldProductTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|OldProduct listsTranslations(string $translationField)
 * @method static Builder|OldProduct newModelQuery()
 * @method static Builder|OldProduct newQuery()
 * @method static Builder|OldProduct notTranslatedIn(?string $locale = null)
 * @method static Builder|OldProduct orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|OldProduct orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|OldProduct orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|OldProduct query()
 * @method static Builder|OldProduct translated()
 * @method static Builder|OldProduct translatedIn(?string $locale = null)
 * @method static Builder|OldProduct whereAddedBy($value)
 * @method static Builder|OldProduct whereCalories($value)
 * @method static Builder|OldProduct whereCarbs($value)
 * @method static Builder|OldProduct whereCreatedAt($value)
 * @method static Builder|OldProduct whereDeletedAt($value)
 * @method static Builder|OldProduct whereDiscount($value)
 * @method static Builder|OldProduct whereDiscountDeadline($value)
 * @method static Builder|OldProduct whereDiscountType($value)
 * @method static Builder|OldProduct whereEstimatedTimeToPrepare($value)
 * @method static Builder|OldProduct whereExcelId($value)
 * @method static Builder|OldProduct whereFats($value)
 * @method static Builder|OldProduct whereId($value)
 * @method static Builder|OldProduct wherePrice($value)
 * @method static Builder|OldProduct whereProts($value)
 * @method static Builder|OldProduct whereRating($value)
 * @method static Builder|OldProduct whereRatingCount($value)
 * @method static Builder|OldProduct whereRestaurantId($value)
 * @method static Builder|OldProduct whereSortOrder($value)
 * @method static Builder|OldProduct whereStatus($value)
 * @method static Builder|OldProduct whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|OldProduct whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|OldProduct whereType($value)
 * @method static Builder|OldProduct whereUpdatedAt($value)
 * @method static Builder|OldProduct withTranslation()
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class OldProduct extends OldModel
{
    use Translatable;

    protected $table = 'jo3aan_dishes';
    protected $primaryKey = 'id';
    protected $with = ['translations'];
    protected array $translatedAttributes = ['title', 'description', 'image'];
    protected $translationForeignKey = 'dish_id';

    public const DISCOUNT_PERCENTAGE = 'PERCENTAGE';
    public const DISCOUNT_CASH = 'CASH';
    public const TYPE_MEALS = 'MEALS';
    public const TYPE_SIDE_DISHES = 'SIDE_DISHES';
    public const TYPE_DRINKS = 'DRINKS';
    public const TYPE_FRIES = 'FRIES';
    public const TYPE_SALADS = 'SALADS';
    public const TYPE_SAUCES = 'SAUCES';


    public static function attributesComparing(): array
    {
        return [
            'id' => 'id',
            'price' => 'price',
            'discount' => 'price_discount_amount',
            'discount_type' => 'price_discount_by_percentage',
            'discount_deadline' => 'price_discount_finished_at',
            'rating' => 'avg_rating',
            'rating_count' => 'rating_count',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
        ];
    }


    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(OldCategory::class, 'jo3aan_dishes_categories', 'dish_id', 'category_id');
    }


}
