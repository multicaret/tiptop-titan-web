<?php

namespace App\Models;

use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use App\Traits\HasViewCount;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

/**
 * App\Models\Barcode
 *
 * @property int $id
 * @property int $country_id
 * @property int $creator_id
 * @property int $editor_id
 * @property int $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read bool $image
 * @property-read mixed $is_published
 * @property-read mixed $status_name
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode published()
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode query()
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Barcode extends Model implements HasMedia
{
    use HasMediaTrait,
        HasStatuses,
        HasViewCount;

    const STATUS_INCOMPLETE = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_INACTIVE = 3;

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
