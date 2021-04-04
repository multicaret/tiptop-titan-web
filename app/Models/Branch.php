<?php

namespace App\Models;

use App\Traits\HasAppTypes;
use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use App\Traits\HasTypes;
use App\Traits\HasUuid;
use App\Traits\HasViewCount;
use App\Traits\HasWorkingHours;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo as BelongsTo;
use Multicaret\Acquaintances\Traits\CanBeRated;
use Spatie\MediaLibrary\HasMedia;

/**
 * App\Models\Branch
 *
 * @property int $id
 * @property string $uuid
 * @property int $chain_id
 * @property int $creator_id
 * @property int $editor_id
 * @property int|null $region_id
 * @property int|null $city_id
 * @property bool $has_tip_top_delivery
 * @property float $minimum_order
 * @property float $under_minimum_order_delivery_fee
 * @property float $fixed_delivery_fee
 * @property int $min_delivery_minutes
 * @property int $max_delivery_minutes
 * @property bool $has_restaurant_delivery
 * @property float $restaurant_minimum_order
 * @property float $restaurant_under_minimum_order_delivery_fee
 * @property float $restaurant_fixed_delivery_fee
 * @property int $restaurant_min_delivery_minutes
 * @property int $restaurant_max_delivery_minutes
 * @property string|null $primary_phone_number
 * @property string|null $secondary_phone_number
 * @property string|null $whatsapp_phone_number
 * @property int|null $order_column
 * @property int $type 1:Market, 2: Food
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string $avg_rating
 * @property int $rating_count
 * @property int $view_count
 * @property int $status 1:draft, 2:active, 3:Inactive, 4..n:CUSTOM
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Chain $chain
 * @property-read \App\Models\City|null $city
 * @property-read mixed $average_rating_all_types
 * @property-read mixed $average_rating
 * @property-read bool $has_been_rated
 * @property-read bool $is_active
 * @property-read bool $is_inactive
 * @property-read mixed $status_name
 * @property-read mixed $sum_rating_all_types
 * @property-read mixed $sum_rating
 * @property-read mixed $user_average_rating_all_types
 * @property-read mixed $user_average_rating
 * @property-read mixed $user_sum_rating_all_types
 * @property-read mixed $user_sum_rating
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Location[] $locations
 * @property-read int|null $locations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $managers
 * @property-read int|null $managers_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Multicaret\Acquaintances\Models\InteractionRelation[] $ratings
 * @property-read int|null $ratings_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Multicaret\Acquaintances\Models\InteractionRelation[] $ratingsPure
 * @property-read int|null $ratings_pure_count
 * @property-read \App\Models\Region|null $region
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $supervisors
 * @property-read int|null $supervisors_count
 * @property-read \App\Models\BranchTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BranchTranslation[] $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WorkingHour[] $workingHours
 * @property-read int|null $working_hours_count
 * @method static \Illuminate\Database\Eloquent\Builder|Branch active()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch food()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch grocery()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch notActive()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Branch query()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereAvgRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereChainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereFixedDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereHasRestaurantDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereHasTipTopDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereMaxDeliveryMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereMinDeliveryMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereMinimumOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch wherePrimaryPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereRestaurantFixedDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereRestaurantMaxDeliveryMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereRestaurantMinDeliveryMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereRestaurantMinimumOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereRestaurantUnderMinimumOrderDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereSecondaryPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereUnderMinimumOrderDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereViewCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereWhatsappPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch withTranslation()
 * @mixin \Eloquent
 */
class Branch extends Model implements HasMedia
{
    use CanBeRated;
    use HasAppTypes;
    use HasMediaTrait;
    use HasStatuses;
    use HasTypes;
    use HasUuid;
    use HasViewCount;
    use HasWorkingHours;
    use Translatable;


    const STATUS_DRAFT = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_INACTIVE = 3;

    const TYPE_GROCERY_OBJECT = 1;
    const TYPE_FOOD_OBJECT = 2;

    protected $fillable = [
        'has_tip_top_delivery',
        'minimum_order',
        'under_minimum_order_delivery_fee',
        'fixed_delivery_fee',
        'has_restaurant_delivery',
        'restaurant_minimum_order',
        'restaurant_under_minimum_order_delivery_fee',
        'restaurant_fixed_delivery_fee',
    ];
    protected $with = ['translations'];
    protected $translatedAttributes = ['title', 'description'];

    protected $casts = [
        'has_tip_top_delivery' => 'boolean',
        'has_restaurant_delivery' => 'boolean',
        'minimum_order' => 'double',
        'under_minimum_order_delivery_fee' => 'double',
        'fixed_delivery_fee' => 'double',
        'restaurant_minimum_order' => 'double',
        'restaurant_under_minimum_order_delivery_fee' => 'double',
        'restaurant_fixed_delivery_fee' => 'double',
    ];


    public function chain(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Chain::class, 'chain_id');
    }

    public function managers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'branch_manager', 'manager_id', 'branch_id')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    public function primaryManager()
    {
        return $this->managers()->wherePivot('is_primary', true)->first();
    }

    public function supervisors(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'branch_supervisor', 'supervisor_id', 'branch_id')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    public function primarySupervisor()
    {
        return $this->supervisors()->wherePivot('is_primary', true)->first();
    }

    public function workingHours()
    {
        return $this->morphMany(WorkingHour::class, 'workable');
    }


    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function locations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Location::class, 'contactable_id');
    }

    public static function getClosestAvailableBranch($latitude, $longitude): array
    {
        $distance = $branch = null;
        $branchesOrderedByDistance = Branch::active()
                                           ->selectRaw('branches.id, DISTANCE_BETWEEN(latitude,longitude,?,?) as distance',
                                               [$latitude, $longitude])
                                           ->orderBy('distance')
                                           ->get();

        foreach ($branchesOrderedByDistance as $branchOrderedByDistance) {
            $branch = Branch::find($branchOrderedByDistance->id);
            $branchWorkingHours = WorkingHour::retrieve($branch);
            if ($branchWorkingHours['isOpen']) {
                $distance = $branchOrderedByDistance->distance;
                break;
            }
        }

        return [$distance, $branch];
    }

    public function getHasBeenRatedAttribute(): bool
    {
        return $this->raters->count() > 0;
    }
}
