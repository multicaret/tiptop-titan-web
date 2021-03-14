<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use App\Traits\HasUuid;
use App\Traits\HasViewCount;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasMediaTrait,
        Translatable,
        SoftDeletes,
        HasViewCount,
        HasUuid,
        HasStatuses;

    const STATUS_INCOMPLETE = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_INACTIVE = 3;
    const STATUS_INACTIVE_SEASONABLE = 4;

    const TYPE_GROCERY = 1;
    const TYPE_FOOD = 2;

    protected $with = [
        'translations',
        'tags',
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
        'price_discount_amount' => 'float',
        'price' => 'float',
        'price_discount_by_percentage' => 'boolean',
        'price_discount_began_at' => 'timestamp',
        'price_discount_finished_at' => 'timestamp',
        'custom_banner_began_at' => 'timestamp',
        'custom_banner_ended_at' => 'timestamp',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function chain(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Chain::class, 'chain_id');
    }

    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function masterCategory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Taxonomy::class, 'category_id');
    }

    public function barcodes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Barcode::class, 'barcode_product', 'product_id', 'barcode_id');
    }

    public function unit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Taxonomy::class, 'unit_id');
    }

    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Taxonomy::class, 'category_product', 'product_id', 'category_id');
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Taxonomy::class, 'product_tag', 'product_id', 'tag_id');
    }

    public function baskets(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Basket::class, 'basket_product', 'product_id', 'basket_id');
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
            $image = $media->getUrl('medium');
        }

        if ( ! is_null($media = $this->getFirstMedia('cover'))) {
            //$media->responsive_images
            $image = $media->getUrl('medium');
        }

        return url($image);
    }

    public function getCoverFullAttribute()
    {
        $image = config('defaults.images.product_cover');

        if ( ! is_null($media = $this->getFirstMedia('gallery'))) {
            $image = $media->getUrl();
        }

        if ( ! is_null($media = $this->getFirstMedia('cover'))) {
            $image = $media->getUrl();
        }

        return url($image);
    }

    public function getThumbnailAttribute()
    {
        $image = config('defaults.images.product_cover_small');

        if ( ! is_null($media = $this->getFirstMedia('cover'))) {
            $image = $media->getUrl('thumbnail');
        }

        return url($image);
    }

    public function getGalleryAttribute()
    {
        return $this->getMediaForUploader('gallery', 'medium');
    }


    public function registerMediaCollections(): void
    {
        $isGrocery = $this->type === Product::TYPE_GROCERY;

        $this->addMediaCollection('cover')
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

    public function getDiscountedPrice()
    {
        if ($this->price_discount_by_percentage) {
            $discountAmount = Controller::deductPercentage($this->price, $this->price_discount_amount);
        } else {
            $discountAmount = $this->price - $this->price_discount_amount;
        }

        return $this->price - $discountAmount;
    }

    public function getDiscountedPriceFormattedAttribute(): string
    {
        return Currency::format($this->discounted_price);
    }


}
