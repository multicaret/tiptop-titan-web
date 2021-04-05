<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use App\Traits\HasAppTypes;
use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use App\Traits\HasTypes;
use App\Traits\HasUuid;
use App\Traits\HasViewCount;
use Astrotomic\Translatable\Translatable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Multicaret\Acquaintances\Traits\CanBeFavorited;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $uuid
 * @property int $creator_id
 * @property int $editor_id
 * @property int $chain_id
 * @property int $branch_id
 * @property int $category_id
 * @property int|null $unit_id
 * @property float|null $price
 * @property float|null $price_discount_amount
 * @property int|null $available_quantity
 * @property string|null $sku
 * @property int|null $upc
 * @property float|null $width x
 * @property float|null $height y
 * @property float|null $depth z
 * @property float|null $weight w
 * @property int $type 1:Market, 2: Food
 * @property int|null $minimum_orderable_quantity
 * @property int|null $order_column
 * @property string $avg_rating
 * @property int $rating_count
 * @property int $search_count
 * @property int $view_count
 * @property int $status 1:draft, 2:active, 3:Inactive, 4..n:CUSTOM
 * @property int|null $price_discount_began_at
 * @property int|null $price_discount_finished_at
 * @property int|null $custom_banner_began_at
 * @property int|null $custom_banner_ended_at
 * @property bool|null $is_storage_tracking_enabled
 * @property bool|null $price_discount_by_percentage true: percentage, false: fixed amount
 * @property int $on_mobile_grid_tile_weight
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|Barcode[] $barcodes
 * @property-read int|null $barcodes_count
 * @property Branch $branch
 * @property-read Collection|Cart[] $carts
 * @property-read int|null $carts_count
 * @property-read Collection|Taxonomy[] $categories
 * @property-read int|null $categories_count
 * @property Chain $chain
 * @property-read User $creator
 * @property-read User $editor
 * @property-read Collection|User[] $favoriters
 * @property-read int|null $favoriters_count
 * @property-read mixed $cover
 * @property-read mixed $cover_full
 * @property-read mixed $discounted_price
 * @property-read string $discounted_price_formatted
 * @property-read mixed $gallery
 * @property-read bool $is_active
 * @property-read bool $is_inactive
 * @property-read mixed $price_formatted
 * @property-read mixed $status_name
 * @property-read mixed $thumbnail
 * @property-read Taxonomy $masterCategory
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read Collection|Taxonomy[] $tags
 * @property-read int|null $tags_count
 * @property-read ProductTranslation|null $translation
 * @property-read Collection|ProductTranslation[] $translations
 * @property-read int|null $translations_count
 * @property-read Taxonomy|null $unit
 * @method static Builder|Product active()
 * @method static Builder|Product draft()
 * @method static Builder|Product food()
 * @method static Builder|Product forCategory($categoryId)
 * @method static Builder|Product grocery()
 * @method static Builder|Product inactive()
 * @method static Builder|Product listsTranslations(string $translationField)
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product notActive()
 * @method static Builder|Product notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Query\Builder|Product onlyTrashed()
 * @method static Builder|Product orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Product orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Product orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|Product query()
 * @method static Builder|Product translated()
 * @method static Builder|Product translatedIn(?string $locale = null)
 * @method static Builder|Product whereAvailableQuantity($value)
 * @method static Builder|Product whereAvgRating($value)
 * @method static Builder|Product whereBranchId($value)
 * @method static Builder|Product whereCategoryId($value)
 * @method static Builder|Product whereChainId($value)
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereCreatorId($value)
 * @method static Builder|Product whereCustomBannerBeganAt($value)
 * @method static Builder|Product whereCustomBannerEndedAt($value)
 * @method static Builder|Product whereDeletedAt($value)
 * @method static Builder|Product whereDepth($value)
 * @method static Builder|Product whereEditorId($value)
 * @method static Builder|Product whereHeight($value)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereIsStorageTrackingEnabled($value)
 * @method static Builder|Product whereMinimumOrderableQuantity($value)
 * @method static Builder|Product whereOnMobileGridTileWeight($value)
 * @method static Builder|Product whereOrderColumn($value)
 * @method static Builder|Product wherePrice($value)
 * @method static Builder|Product wherePriceDiscountAmount($value)
 * @method static Builder|Product wherePriceDiscountBeganAt($value)
 * @method static Builder|Product wherePriceDiscountByPercentage($value)
 * @method static Builder|Product wherePriceDiscountFinishedAt($value)
 * @method static Builder|Product whereRatingCount($value)
 * @method static Builder|Product whereSearchCount($value)
 * @method static Builder|Product whereSku($value)
 * @method static Builder|Product whereStatus($value)
 * @method static Builder|Product whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|Product whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Product whereType($value)
 * @method static Builder|Product whereUnitId($value)
 * @method static Builder|Product whereUpc($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @method static Builder|Product whereUuid($value)
 * @method static Builder|Product whereViewCount($value)
 * @method static Builder|Product whereWeight($value)
 * @method static Builder|Product whereWidth($value)
 * @method static Builder|Product withTranslation()
 * @method static \Illuminate\Database\Query\Builder|Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Product withoutTrashed()
 * @mixin Eloquent
 */
class Product extends Model implements HasMedia
{
    use CanBeFavorited;
    use HasAppTypes;
    use HasMediaTrait;
    use HasStatuses;
    use HasTypes;
    use HasUuid;
    use HasViewCount;
    use SoftDeletes;
    use Translatable;


    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_INACTIVE = 3;
    public const STATUS_INACTIVE_SEASONABLE = 4;

    public const TYPE_GROCERY_OBJECT = 1;
    public const TYPE_FOOD_OBJECT = 2;

    protected $with = [
        'chain',
        'branch',
        'translations',
        'tags',
        'categories',
        'masterCategory'
    ];

    protected $fillable = ['order_column'];

    protected $translatedAttributes = ['title', 'description', 'excerpt', 'notes'];

    protected $appends = [
        'thumbnail',
        'cover',
        'gallery',
        'price_formatted',
        'discounted_price',
        'discounted_price_formatted',
    ];

    protected $casts = [
        'price_discount_amount' => 'double',
        'price' => 'double',
        'is_storage_tracking_enabled' => 'boolean',
        'price_discount_by_percentage' => 'boolean',
        'price_discount_began_at' => 'datetime',
        'price_discount_finished_at' => 'datetime',
        'custom_banner_began_at' => 'datetime',
        'custom_banner_ended_at' => 'datetime',
        'width' => 'float',
        'height' => 'float',
        'depth' => 'float',
        'weight' => 'float',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function chain(): BelongsTo
    {
        return $this->belongsTo(Chain::class, 'chain_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function masterCategory(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class, 'category_id');
    }

    public function barcodes(): BelongsToMany
    {
        return $this->belongsToMany(Barcode::class, 'barcode_product', 'product_id', 'barcode_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class, 'unit_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Taxonomy::class, 'category_product', 'product_id', 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Taxonomy::class, 'product_tag', 'product_id', 'tag_id');
    }

    public function carts(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class, 'cart_product', 'product_id', 'cart_id');
    }


    public static function getAllStatusesRich(): array
    {
        return [
            self::STATUS_ACTIVE => [
                'id' => self::STATUS_ACTIVE,
                'title' => __('Active'),
                'class' => 'success',
            ],
            self::STATUS_INACTIVE => [
                'id' => self::STATUS_INACTIVE,
                'title' => __('Inactive'),
                'class' => 'danger',
            ],
            self::STATUS_INACTIVE_SEASONABLE => [
                'id' => self::STATUS_INACTIVE_SEASONABLE,
                'title' => __('Inactive Seasonable'),
                'class' => 'warning',
            ],
        ];
    }

    /**
     *
     * @param  Builder  $query
     *
     * @param $categoryId
     * @return Builder
     */
    public function scopeForCategory($query, $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    public function getCoverAttribute()
    {
        $image = config('defaults.images.product_cover');

        if ( ! is_null($media = $this->getFirstMedia('gallery'))) {
            $image = $media->getUrl('1K');
        }

        if ( ! is_null($media = $this->getFirstMedia('cover'))) {
            //$media->responsive_images
            $image = $media->getUrl('1K');
        }

        return url($image);
    }

    public function getCoverFullAttribute()
    {
        return $this->getFirstMediaUrl('cover', 'HD');
    }

    public function getThumbnailAttribute()
    {
        return $this->getFirstMediaUrl('cover', 'SD');
    }

    public function getGalleryAttribute()
    {
        return $this->getMediaForUploader('gallery', 'HD');
    }


    public function registerMediaCollections(): void
    {
        $isGrocery = $this->type === self::TYPE_GROCERY_OBJECT;
        $fallBackImageUrl = config('defaults.images.product_cover');
        $this->addMediaCollection('cover')
             ->useFallbackUrl(url($fallBackImageUrl))
             ->singleFile()
             ->withResponsiveImages()
             ->registerMediaConversions(function (Media $media) use ($isGrocery) {
                 if ($isGrocery) {
                     foreach (config('defaults.image_conversions.product_grocery_cover') as $conversionName => $dimensions) {
                         $this->addMediaConversion($conversionName)
                              ->width($dimensions['width'])
                              ->height($dimensions['height']);
                     }
                 } else {
                     foreach (config('defaults.image_conversions.generic_cover') as $conversionName => $dimensions) {
                         $this->addMediaConversion($conversionName)
                              ->width($dimensions['width'])
                              ->height($dimensions['height']);
                     }
                 }
             });


        $this->addMediaCollection('gallery')
             ->withResponsiveImages()
             ->registerMediaConversions(function (Media $media) use ($isGrocery) {
                 if ($isGrocery) {
                     foreach (config('defaults.image_conversions.product_grocery_cover') as $conversionName => $dimensions) {
                         $this->addMediaConversion($conversionName)
                              ->width($dimensions['width'])
                              ->height($dimensions['height']);
                     }
                 } else {
                     foreach (config('defaults.image_conversions.generic_cover') as $conversionName => $dimensions) {
                         $this->addMediaConversion($conversionName)
                              ->width($dimensions['width'])
                              ->height($dimensions['height']);
                     }
                 }
             });

    }

    public function getPriceFormattedAttribute()
    {
        return Currency::format($this->price);
    }

    public function getDiscountedPriceAttribute()
    {
        return Controller::calculateDiscountedAmount($this->price, $this->price_discount_amount,
            $this->price_discount_by_percentage);

    }

    public function getDiscountedPriceFormattedAttribute(): string
    {
        return Currency::format($this->discounted_price);
    }


    public static function getDroppedColumns(): array
    {
        return $droppedColumns = [
            'avg_rating',
            'created_at',
            'creator_id',
            'deleted_at',
            'editor_id',
            'id',
            'order_column',
            'rating_count',
            'search_count',
            'sku',
            'type',
            'on_mobile_grid_tile_weight',
            'status',
            'upc',
            'updated_at',
            'uuid',
            'view_count',
            'product_id',
            'locale',
            'excerpt',
            'width',
            'height',
            'depth',
            'weight',
        ];
    }


}
