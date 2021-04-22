<?php

namespace App\Models;

use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use App\Traits\HasViewCount;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

/**
 * App\Models\Barcode
 *
 * @property int $id
 * @property int $country_id
 * @property int $creator_id
 * @property int $editor_id
 * @property int $code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read bool $image
 * @property-read bool $is_active
 * @property-read bool $is_inactive
 * @property-read mixed $status_name
 * @property-read MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @method static Builder|Barcode active()
 * @method static Builder|Barcode draft()
 * @method static Builder|Barcode inactive()
 * @method static Builder|Barcode newModelQuery()
 * @method static Builder|Barcode newQuery()
 * @method static Builder|Barcode notActive()
 * @method static Builder|Barcode query()
 * @method static Builder|Barcode whereCode($value)
 * @method static Builder|Barcode whereCountryId($value)
 * @method static Builder|Barcode whereCreatedAt($value)
 * @method static Builder|Barcode whereCreatorId($value)
 * @method static Builder|Barcode whereEditorId($value)
 * @method static Builder|Barcode whereId($value)
 * @method static Builder|Barcode whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read array $status_js
 */
class Barcode extends Model implements HasMedia
{
    use HasMediaTrait;
    use HasStatuses;
    use HasViewCount;


    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_INACTIVE = 3;

    protected $appends = [
        'image',
    ];

    /**
     * Get the image attribute.
     *
     * @param  string  $image
     *
     * @return bool
     */
    public function getImageAttribute()
    {
        $image = url(config('defaults.images.barcode_image'));

        if ( ! is_null($media = $this->getFirstMedia('image'))) {
            $image = $media->getFullUrl('thumbnail');
        }

        return $image;
    }


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
             ->singleFile()
             ->registerMediaConversions(function (Media $media) {
                 $this->addMediaConversion('medium')
                      ->width(1024)
                      ->height(512);
                 $this->addMediaConversion('thumbnail')
                      ->width(512)
                      ->height(256);
             });
    }

}
