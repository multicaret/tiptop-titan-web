<?php

namespace App\Models;

use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use App\Traits\HasViewCount;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Manufacturer
 *
 * @property int $id
 * @property int $creator_id
 * @property int $editor_id
 * @property int|null $region_id
 * @property int|null $city_id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read bool $is_active
 * @property-read bool $is_inactive
 * @property-read bool $logo
 * @property-read array $status_js
 * @property-read mixed $status_name
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @method static Builder|Manufacturer active()
 * @method static Builder|Manufacturer draft()
 * @method static Builder|Manufacturer inactive()
 * @method static Builder|Manufacturer newModelQuery()
 * @method static Builder|Manufacturer newQuery()
 * @method static Builder|Manufacturer notActive()
 * @method static Builder|Manufacturer query()
 * @method static Builder|Manufacturer whereCityId($value)
 * @method static Builder|Manufacturer whereCreatedAt($value)
 * @method static Builder|Manufacturer whereCreatorId($value)
 * @method static Builder|Manufacturer whereEditorId($value)
 * @method static Builder|Manufacturer whereId($value)
 * @method static Builder|Manufacturer whereName($value)
 * @method static Builder|Manufacturer whereRegionId($value)
 * @method static Builder|Manufacturer whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Manufacturer extends Model implements HasMedia
{
    use HasMediaTrait;
    use HasStatuses;
    use HasViewCount;


    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_INACTIVE = 3;

    protected $appends = [
        'logo',
    ];

    /**
     * Get the logo attribute.
     *
     * @param  string  $logo
     *
     * @return bool
     */
    public function getLogoAttribute()
    {
        $logo = url(config('defaults.images.manufacturer_logo'));

        if ( ! is_null($media = $this->getFirstMedia('logo'))) {
            $logo = $media->getFullUrl('1K');
        }

        return $logo;
    }


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
             ->singleFile()
             ->withResponsiveImages()
             ->registerMediaConversions(function (Media $media) {
                 foreach (config('defaults.image_conversions.generic_logo') as $conversionName => $dimensions) {
                     $this->addMediaConversion($conversionName)
                          ->width($dimensions['width'])
                          ->height($dimensions['height']);
                 }
                 $this->addMediaConversion('256-cropped')
                      ->crop(Manipulations::CROP_CENTER, 256, 256);
             });

    }

}
