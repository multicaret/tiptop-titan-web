<?php

namespace App\Models;

use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use Astrotomic\Translatable\Translatable;
use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\PaymentMethod
 *
 * @property int $id
 * @property int $creator_id
 * @property int $editor_id
 * @property float $base_commission
 * @property int $status 1:draft, 2:active, 3:Inactive, 4..n:CUSTOM
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\User $editor
 * @property-read bool $is_active
 * @property-read bool $is_inactive
 * @property-read bool $logo
 * @property-read mixed $status_class
 * @property-read mixed $status_name
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read \App\Models\PaymentMethodTranslation|null $translation
 * @property-read Collection|\App\Models\PaymentMethodTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod active()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod draft()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod notActive()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod notDraft()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod notTranslatedIn(?string $locale = null)
 * @method static Builder|PaymentMethod onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod translated()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereBaseCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod withTranslation()
 * @method static Builder|PaymentMethod withTrashed()
 * @method static Builder|PaymentMethod withoutTrashed()
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class PaymentMethod extends Model implements HasMedia
{
    use HasMediaTrait;
    use HasStatuses;
    use SoftDeletes;
    use Translatable;


    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_INACTIVE = 3;

    protected $appends = [
        'logo',
    ];

    protected $casts = [
        'instructions' => 'json',
        'base_commission' => 'double',
    ];

    protected $with = [
        'translations',
    ];
    protected $translatedAttributes = [
        'title',
        'description',
        'instructions'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id')->withTrashed();
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id')->withTrashed();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
             ->singleFile()
             ->withResponsiveImages()
             ->registerMediaConversions(function (Media $media) {
                 foreach (config('defaults.image_conversions.icon') as $conversionName => $dimensions) {
                     $this->addMediaConversion($conversionName)
                          ->format(Manipulations::FORMAT_PNG)
                          ->width($dimensions['width'])
                          ->height($dimensions['height']);
                 }
                 $this->addMediaConversion('256-cropped')
                      ->format(Manipulations::FORMAT_PNG)
                      ->crop(Manipulations::CROP_CENTER, 256, 256);
             });

    }

    /**
     * Get the logo attribute.
     *
     * @param  string  $logo
     *
     * @return bool
     */
    public function getLogoAttribute()
    {
        $logo = url(config('defaults.images.payment_method_logo'));

        if ( ! is_null($media = $this->getFirstMedia('logo'))) {
            $logo = $media->getFullUrl(/*'256'*/);
        }

        return $logo;
    }

}
