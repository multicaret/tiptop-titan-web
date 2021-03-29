<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use App\Traits\HasTypes;
use App\Traits\HasUuid;
use App\Traits\HasViewCount;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Multicaret\Acquaintances\Traits\CanBeFavorited;
use Spatie\MediaLibrary\HasMedia;
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
 * @property bool|null $price_discount_by_percentage true: percentage, false: fixed amount
 * @property int|null $available_quantity
 * @property string|null $sku
 * @property int|null $upc
 * @property int|null $is_storage_tracking_enabled
 * @property float|null $width x
 * @property float|null $height y
 * @property float|null $depth z
 * @property float|null $weight w
 * @property int|null $minimum_orderable_quantity
 * @property int|null $order_column
 * @property string $avg_rating
 * @property int $rating_count
 * @property int $search_count
 * @property int $view_count
 * @property int|null $status
 * @property int|null $price_discount_began_at
 * @property int|null $price_discount_finished_at
 * @property int|null $custom_banner_began_at
 * @property int|null $custom_banner_ended_at
 * @property int $on_mobile_grid_tile_weight
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Barcode[] $barcodes
 * @property-read int|null $barcodes_count
 * @property \App\Models\Branch $branch
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Cart[] $carts
 * @property-read int|null $carts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Taxonomy[] $categories
 * @property-read int|null $categories_count
 * @property \App\Models\Chain $chain
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\User $editor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $favoriters
 * @property-read int|null $favoriters_count
 * @property-read mixed $cover
 * @property-read mixed $cover_full
 * @property-read mixed $discounted_price
 * @property-read string $discounted_price_formatted
 * @property-read mixed $gallery
 * @property-read mixed $is_published
 * @property-read mixed $price_formatted
 * @property-read mixed $status_name
 * @property-read mixed $thumbnail
 * @property-read \App\Models\Taxonomy $masterCategory
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Taxonomy[] $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\ProductTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductTranslation[] $translations
 * @property-read int|null $translations_count
 * @property-read \App\Models\Taxonomy|null $unit
 * @method static \Illuminate\Database\Eloquent\Builder|Product draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Product forCategory($categoryId)
 * @method static \Illuminate\Database\Eloquent\Builder|Product inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Product incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|Product listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|Product notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Query\Builder|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Product orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Product orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Product published()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Product translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAvailableQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAvgRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereChainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCustomBannerBeganAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCustomBannerEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsStorageTrackingEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMinimumOrderableQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereOnMobileGridTileWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePriceDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePriceDiscountBeganAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePriceDiscountByPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePriceDiscountFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSearchCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereViewCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product withTranslation()
 * @method static \Illuminate\Database\Query\Builder|Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Product withoutTrashed()
 * @mixin \Eloquent
 * @property int $type 1:Market, 2: Food
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereType($value)
 */
class Product extends Model implements HasMedia
{
    use HasMediaTrait,
        Translatable,
        SoftDeletes,
        HasViewCount,
        HasUuid,
        HasStatuses,
        HasTypes,
        CanBeFavorited;

    const STATUS_INCOMPLETE = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_INACTIVE = 3;
    const STATUS_INACTIVE_SEASONABLE = 4;

    const TYPE_GROCERY_PRODUCT = 1;
    const TYPE_FOOD_PRODUCT = 2;

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
        'price_discount_began_at' => 'timestamp',
        'price_discount_finished_at' => 'timestamp',
        'custom_banner_began_at' => 'timestamp',
        'custom_banner_ended_at' => 'timestamp',
        'width' => 'float',
        'height' => 'float',
        'depth' => 'float',
        'weight' => 'float',
    ];

    public static function requestTypeIsGrocery(): bool
    {
        return request()->type === self::getTypesArray()[self::TYPE_GROCERY_PRODUCT];
    }

    public static function requestTypeIsFood(): bool
    {
        return request()->type === self::getTypesArray()[self::TYPE_FOOD_PRODUCT];
    }

    public static function checkRequestTypes(): object
    {
        return new class {
            public static function isFood(): bool
            {
                return request()->type === Product::getTypesArray()[Product::TYPE_FOOD_PRODUCT];
            }

            public static function isGrocery(): bool
            {
                return request()->type === Product::getTypesArray()[Product::TYPE_GROCERY_PRODUCT];
            }
        };
    }

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
            self::STATUS_PUBLISHED => [
                'id' => self::STATUS_PUBLISHED,
                'title' => __("Active"),
                'class' => 'success',
            ],
            self::STATUS_INACTIVE => [
                'id' => self::STATUS_INACTIVE,
                'title' => __("Inactive"),
                'class' => 'danger',
            ],
            self::STATUS_INACTIVE_SEASONABLE => [
                'id' => self::STATUS_INACTIVE_SEASONABLE,
                'title' => __("Inactive Seasonable"),
                'class' => 'warning',
            ],
        ];
    }

    /**
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @param $categoryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCategory($query, $categoryId): \Illuminate\Database\Eloquent\Builder
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
        $isGrocery = $this->type === self::TYPE_GROCERY_PRODUCT;
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
        if ( ! is_null($this->price_discount_amount) || $this->price_discount_amount != 0) {
            if ($this->price_discount_by_percentage) {
                $discountAmount = Controller::deductPercentage($this->price, $this->price_discount_amount);
            } else {
                $discountAmount = $this->price - $this->price_discount_amount;
            }

            return $this->price - $discountAmount;
        } else {
            return null;
        }
    }

    public function getDiscountedPriceFormattedAttribute(): string
    {
        return Currency::format($this->discounted_price);
    }

    public static function getTypesArray(): array
    {
        return [
            self::TYPE_GROCERY_PRODUCT => 'grocery-product',
            self::TYPE_FOOD_PRODUCT => 'food-product',
        ];
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
