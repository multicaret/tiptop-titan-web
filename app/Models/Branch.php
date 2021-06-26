<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use App\Traits\HasAppTypes;
use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use App\Traits\HasTypes;
use App\Traits\HasUuid;
use App\Traits\HasViewCount;
use App\Traits\HasWorkingHours;
use Astrotomic\Translatable\Translatable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo as BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;
use Multicaret\Acquaintances\Models\InteractionRelation;
use Multicaret\Acquaintances\Traits\CanBeFavorited;
use Multicaret\Acquaintances\Traits\CanBeRated;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

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
 * @property int $free_delivery_threshold
 * @property int $extra_delivery_fee_per_km
 * @property bool $has_restaurant_delivery
 * @property float $restaurant_minimum_order
 * @property float $restaurant_under_minimum_order_delivery_fee
 * @property float $restaurant_fixed_delivery_fee
 * @property int $restaurant_min_delivery_minutes
 * @property int $restaurant_max_delivery_minutes
 * @property int $restaurant_free_delivery_threshold
 * @property int $restaurant_extra_delivery_fee_per_km
 * @property int $management_commission_rate 0 means there is no commission atall
 * @property bool $is_open_now
 * @property string|null $primary_phone_number
 * @property string|null $secondary_phone_number
 * @property string|null $whatsapp_phone_number
 * @property int|null $order_column
 * @property int $type 1:Market, 2: Food
 * @property string|null $full_address
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string $avg_rating
 * @property int $rating_count
 * @property int $view_count
 * @property int $status 1:draft, 2:active, 3:Inactive, 4..n:CUSTOM
 * @property Carbon|null $published_at
 * @property Carbon|null $featured_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $zoho_books_id
 * @property string|null $zoho_books_account_id
 * @property string|null $zoho_books_tiptop_delivery_item_id
 * @property string|null $zoho_books_delivery_item_id
 * @property bool $has_jet_delivery
 * @property float $jet_minimum_order
 * @property float $jet_fixed_delivery_fee
 * @property float $jet_delivery_commission_rate
 * @property float $jet_extra_delivery_fee_per_km
 * @property-read Collection|\App\Models\Location[] $addresses
 * @property-read int|null $addresses_count
 * @property-read \App\Models\Chain $chain
 * @property-read \App\Models\City|null $city
 * @property-read Collection|\App\Models\Location[] $contacts
 * @property-read int|null $contacts_count
 * @property-read Collection|\App\Models\User[] $drivers
 * @property-read int|null $drivers_count
 * @property-read Collection|\App\Models\User[] $favoriters
 * @property-read int|null $favoriters_count
 * @property-read Collection|\App\Models\Taxonomy[] $foodCategories
 * @property-read int|null $food_categories_count
 * @property-read mixed $average_rating_all_types
 * @property-read mixed $average_rating
 * @property-read bool $has_been_rated
 * @property-read bool $is_active
 * @property-read bool $is_food
 * @property-read bool $is_grocery
 * @property-read bool $is_inactive
 * @property-read mixed $status_name
 * @property-read mixed $sum_rating_all_types
 * @property-read mixed $sum_rating
 * @property-read mixed $user_average_rating_all_types
 * @property-read mixed $user_average_rating
 * @property-read mixed $user_sum_rating_all_types
 * @property-read mixed $user_sum_rating
 * @property-read Collection|\App\Models\Taxonomy[] $groceryCategories
 * @property-read int|null $grocery_categories_count
 * @property-read Collection|\App\Models\Location[] $locations
 * @property-read int|null $locations_count
 * @property-read Collection|\App\Models\User[] $managers
 * @property-read int|null $managers_count
 * @property-read MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read Collection|\App\Models\Taxonomy[] $menuCategories
 * @property-read int|null $menu_categories_count
 * @property-read Collection|\App\Models\User[] $owners
 * @property-read int|null $owners_count
 * @property-read Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read Collection|InteractionRelation[] $ratings
 * @property-read int|null $ratings_count
 * @property-read Collection|InteractionRelation[] $ratingsPure
 * @property-read int|null $ratings_pure_count
 * @property-read \App\Models\Region|null $region
 * @property-read Collection|\App\Models\Taxonomy[] $searchTags
 * @property-read int|null $search_tags_count
 * @property-read \App\Models\BranchTranslation|null $translation
 * @property-read Collection|\App\Models\BranchTranslation[] $translations
 * @property-read int|null $translations_count
 * @property-read Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @property-read Collection|\App\Models\WorkingHour[] $workingHours
 * @property-read int|null $working_hours_count
 * @method static Builder|Branch active()
 * @method static Builder|Branch draft()
 * @method static Builder|Branch foods()
 * @method static Builder|Branch groceries()
 * @method static Builder|Branch inactive()
 * @method static Builder|Branch listsTranslations(string $translationField)
 * @method static Builder|Branch newModelQuery()
 * @method static Builder|Branch newQuery()
 * @method static Builder|Branch notActive()
 * @method static Builder|Branch notDraft()
 * @method static Builder|Branch notTranslatedIn(?string $locale = null)
 * @method static Builder|Branch orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Branch orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Branch orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|Branch query()
 * @method static Builder|Branch translated()
 * @method static Builder|Branch translatedIn(?string $locale = null)
 * @method static Builder|Branch whereAvgRating($value)
 * @method static Builder|Branch whereChainId($value)
 * @method static Builder|Branch whereCityId($value)
 * @method static Builder|Branch whereCreatedAt($value)
 * @method static Builder|Branch whereCreatorId($value)
 * @method static Builder|Branch whereDeletedAt($value)
 * @method static Builder|Branch whereEditorId($value)
 * @method static Builder|Branch whereExtraDeliveryFeePerKm($value)
 * @method static Builder|Branch whereFeaturedAt($value)
 * @method static Builder|Branch whereFixedDeliveryFee($value)
 * @method static Builder|Branch whereFreeDeliveryThreshold($value)
 * @method static Builder|Branch whereFullAddress($value)
 * @method static Builder|Branch whereHasJetDelivery($value)
 * @method static Builder|Branch whereHasRestaurantDelivery($value)
 * @method static Builder|Branch whereHasTipTopDelivery($value)
 * @method static Builder|Branch whereId($value)
 * @method static Builder|Branch whereIsOpenNow($value)
 * @method static Builder|Branch whereJetDeliveryCommissionRate($value)
 * @method static Builder|Branch whereJetExtraDeliveryFeePerKm($value)
 * @method static Builder|Branch whereJetFixedDeliveryFee($value)
 * @method static Builder|Branch whereJetMinimumOrder($value)
 * @method static Builder|Branch whereLatitude($value)
 * @method static Builder|Branch whereLongitude($value)
 * @method static Builder|Branch whereManagementCommissionRate($value)
 * @method static Builder|Branch whereMaxDeliveryMinutes($value)
 * @method static Builder|Branch whereMinDeliveryMinutes($value)
 * @method static Builder|Branch whereMinimumOrder($value)
 * @method static Builder|Branch whereOrderColumn($value)
 * @method static Builder|Branch wherePrimaryPhoneNumber($value)
 * @method static Builder|Branch wherePublishedAt($value)
 * @method static Builder|Branch whereRatingCount($value)
 * @method static Builder|Branch whereRegionId($value)
 * @method static Builder|Branch whereRestaurantExtraDeliveryFeePerKm($value)
 * @method static Builder|Branch whereRestaurantFixedDeliveryFee($value)
 * @method static Builder|Branch whereRestaurantFreeDeliveryThreshold($value)
 * @method static Builder|Branch whereRestaurantMaxDeliveryMinutes($value)
 * @method static Builder|Branch whereRestaurantMinDeliveryMinutes($value)
 * @method static Builder|Branch whereRestaurantMinimumOrder($value)
 * @method static Builder|Branch whereRestaurantUnderMinimumOrderDeliveryFee($value)
 * @method static Builder|Branch whereSecondaryPhoneNumber($value)
 * @method static Builder|Branch whereStatus($value)
 * @method static Builder|Branch whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|Branch whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Branch whereType($value)
 * @method static Builder|Branch whereUnderMinimumOrderDeliveryFee($value)
 * @method static Builder|Branch whereUpdatedAt($value)
 * @method static Builder|Branch whereUuid($value)
 * @method static Builder|Branch whereViewCount($value)
 * @method static Builder|Branch whereWhatsappPhoneNumber($value)
 * @method static Builder|Branch whereZohoBooksAccountId($value)
 * @method static Builder|Branch whereZohoBooksDeliveryItemId($value)
 * @method static Builder|Branch whereZohoBooksId($value)
 * @method static Builder|Branch whereZohoBooksTiptopDeliveryItemId($value)
 * @method static Builder|Branch withTranslation()
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
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
    use CanBeFavorited;


    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_INACTIVE = 3;

    public const CHANNEL_GROCERY_OBJECT = 1;
    public const CHANNEL_FOOD_OBJECT = 2;

    protected $fillable = [
        'has_tip_top_delivery',
        'minimum_order',
        'under_minimum_order_delivery_fee',
        'fixed_delivery_fee',
        'has_restaurant_delivery',
        'restaurant_minimum_order',
        'restaurant_under_minimum_order_delivery_fee',
        'restaurant_fixed_delivery_fee',
        'free_delivery_threshold',
        'restaurant_free_delivery_threshold',
        'published_at',
        'featured_at',
    ];
    protected $with = ['translations'];
    protected $translatedAttributes = ['title', 'description'];

    protected $casts = [
        'has_tip_top_delivery' => 'boolean',
        'has_restaurant_delivery' => 'boolean',
        'has_jet_delivery' => 'boolean',
        'is_open_now' => 'boolean',

        'minimum_order' => 'double',
        'under_minimum_order_delivery_fee' => 'double',
        'fixed_delivery_fee' => 'double',
        'restaurant_minimum_order' => 'double',
        'restaurant_under_minimum_order_delivery_fee' => 'double',
        'restaurant_fixed_delivery_fee' => 'double',
        'jet_fixed_delivery_fee' => 'double',
        'jet_minimum_order' => 'double',
        'jet_delivery_commission_rate' => 'double',
        'jet_extra_delivery_fee_per_km' => 'double',

        'published_at' => 'datetime',
        'featured_at' => 'datetime',
    ];

    public static function getClosestAvailableBranch($latitude, $longitude): array
    {
        return cache()
            ->tags('branches', 'api-home')
            ->rememberForever('getClosestAvailableBranch_lat_'.$latitude.'_lng_'.$longitude, function () use (
                $longitude,
                $latitude
            ) {
                $distance = $branch = $branchTodayWorkingHours = null;
                $branchesOrderedByDistance = self::getBranchesOrderByDistance($latitude, $longitude);

                foreach ($branchesOrderedByDistance as $branchOrderedByDistance) {
                    $branchTodayWorkingHours = WorkingHour::retrieve($branchOrderedByDistance);
                    if ($branchTodayWorkingHours['isOpen']) {
                        $distance = $branchOrderedByDistance->distance;
                        $branch = $branchOrderedByDistance;
                        break;
                    }
                }

                return [$distance, $branch, $branchTodayWorkingHours];
            });
    }

    /**
     * @param $latitude
     * @param $longitude
     * @return Builder[]|Collection|\Illuminate\Support\Collection
     */
    public static function getBranchesOrderByDistance($latitude, $longitude)
    {
        return Branch::active()
                     ->groceries()
                     ->selectRaw('branches.id, DISTANCE_BETWEEN(latitude,longitude,?,?) as distance',
                         [$latitude, $longitude])
                     ->orderBy('distance')
                     ->get();
    }

    public function chain(): BelongsTo
    {
        return $this->belongsTo(Chain::class, 'chain_id');
    }

    public function primaryManager()
    {
        return $this->managers()->wherePivot('is_primary', true)->first();
    }

    public function managers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'branch_manager', 'branch_id', 'user_id')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    public function primaryOwner()
    {
        return $this->owners()->wherePivot('is_primary', true)->first();
    }

    public function owners(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'branch_owner', 'branch_id', 'user_id')
                    ->withPivot('is_primary')
                    ->withTimestamps();
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

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class, 'contactable_id');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Location::class, 'contactable_id')->where('type', Location::TYPE_CONTACT);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Location::class, 'contactable_id')->where('type', Location::TYPE_ADDRESS);
    }

    public function drivers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'branch_driver', 'branch_id', 'user_id')
                    ->withTimestamps();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'branch_user', 'branch_id',
            'user_id')->withTimestamps();
    }

    public function groceryCategories(): BelongsToMany
    {
        return $this->belongsToMany(Taxonomy::class, 'category_branch', 'branch_id',
            'category_id')->withTimestamps();
    }

    public function foodCategories(): BelongsToMany
    {
        return $this->belongsToMany(Taxonomy::class, 'category_branch', 'branch_id',
            'category_id')->withTimestamps();
    }

    public function menuCategories(): HasMany
    {
        return $this->hasMany(Taxonomy::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function searchTags(): MorphToMany
    {
        return $this->morphToMany(Taxonomy::class, 'search_taggable')
                    ->withTimestamps();
    }

    public function getHasBeenRatedAttribute(): bool
    {
        return $this->raters->count() > 0;
    }


    public function validateCartValue($totalAmount, $isTipTopDelivery = true)
    {
        if ( ! $isTipTopDelivery) {
            $freeDeliveryThreshold = $this->restaurant_free_delivery_threshold;
            $minimumOrder = $this->restaurant_minimum_order;
            if ( ! $this->has_restaurant_delivery) {
                return [
                    'isValid' => false,
                    'message' => trans('api.branch_doesnt_has_restaurant_delivery'),
                ];
            }
        } else {
            $freeDeliveryThreshold = $this->free_delivery_threshold;
            $minimumOrder = $this->minimum_order;
            if ( ! $this->has_tip_top_delivery) {
                return [
                    'isValid' => false,
                    'message' => trans('api.branch_doesnt_has_tip_top_delivery'),
                ];
            }
        }

        if ($totalAmount >= $freeDeliveryThreshold) {
            return [
                'isValid' => true,
                'message' => null,
            ];
        }

        if ($minimumOrder > 0) {
            if ($totalAmount < $minimumOrder && $this->under_minimum_order_delivery_fee == 0) {
                return [
                    'isValid' => false,
                    'message' => trans('api.cart_total_under_minimum'),
                ];
            }
        }

        return [
            'isValid' => true,
            'message' => null,
        ];
    }

    public function calculateDeliveryFee(
        $totalAmount,
        $isTipTopDelivery = true,
        $hasCouponWithFreeDelivery = false,
        $addressId = null
    ): float {

        if ($hasCouponWithFreeDelivery) {
            return 0;
        }
        if ( ! $isTipTopDelivery) {
            $minimumOrder = $this->restaurant_minimum_order;
            $fixedDeliveryFee = $this->restaurant_fixed_delivery_fee;
            $underMinimumOrderDeliveryFee = $this->restaurant_under_minimum_order_delivery_fee;
            $freeDeliveryThreshold = $this->restaurant_free_delivery_threshold;
            $extraDeliveryFeePerKm = $this->restaurant_extra_delivery_fee_per_km;
            $fixedDeliveryDistanceKeyName = 'tiptop_fixed_delivery_distance';
        } else {
            $minimumOrder = $this->minimum_order;
            $fixedDeliveryFee = $this->fixed_delivery_fee;
            $underMinimumOrderDeliveryFee = $this->under_minimum_order_delivery_fee;
            $freeDeliveryThreshold = $this->free_delivery_threshold;
            $extraDeliveryFeePerKm = $this->extra_delivery_fee_per_km;
            $fixedDeliveryDistanceKeyName = 'restaurant_fixed_delivery_distance';
        }

        if ( ! is_null($freeDeliveryThreshold) &&
            $freeDeliveryThreshold != 0 &&
            $totalAmount >= $freeDeliveryThreshold) {
            return 0;
        }

        $deliveryFee = $fixedDeliveryFee;

        // Calculating Delivery Fee Based on Distance
        if (
            ! is_null($addressId) &&
            ! is_null($address = Location::where('id', $addressId)->first()) &&
            ! is_null($address->latitude) &&
            ! is_null($address->longitude)
        ) {
            $distance = Controller::distanceBetween($this->latitude, $this->longitude, $address->latitude,
                $address->longitude);
            $distanceOfFixedDeliveryFeeForKMs = Preference::retrieveValue($fixedDeliveryDistanceKeyName);
            if ($distance != 0 && $distanceOfFixedDeliveryFeeForKMs && $distance > $distanceOfFixedDeliveryFeeForKMs) {
                $differenceInDistance = $distance - $distanceOfFixedDeliveryFeeForKMs;
                $deliveryFee = round($deliveryFee + ($extraDeliveryFeePerKm * $differenceInDistance));
            }
        }

        if ($underMinimumOrderDeliveryFee > 0 && $totalAmount < $minimumOrder) {
            $deliveryFee += $underMinimumOrderDeliveryFee;
        }

        return $deliveryFee;
    }


    public function calculatePlainDeliveryFeeForAnAddress($address, $isTipTopDelivery = true): array
    {
        if ( ! $isTipTopDelivery) {
            $fixedDeliveryFee = $this->restaurant_fixed_delivery_fee;
            $extraDeliveryFeePerKm = $this->restaurant_extra_delivery_fee_per_km;
            $fixedDeliveryDistanceKeyName = 'tiptop_fixed_delivery_distance';
        } else {
            $fixedDeliveryFee = $this->fixed_delivery_fee;
            $extraDeliveryFeePerKm = $this->extra_delivery_fee_per_km;
            $fixedDeliveryDistanceKeyName = 'restaurant_fixed_delivery_distance';
        }

        $deliveryFee = $fixedDeliveryFee;

        // Calculating Delivery Fee Based on Distance
        if (
            ! is_null($address) &&
            ! is_null($address->latitude) &&
            ! is_null($address->longitude)
        ) {
            $distance = Controller::distanceBetween($this->latitude, $this->longitude, $address->latitude,
                $address->longitude);
            $distanceOfFixedDeliveryFeeForKMs = Preference::retrieveValue($fixedDeliveryDistanceKeyName);
            if ($distance != 0 && $distanceOfFixedDeliveryFeeForKMs && $distance > $distanceOfFixedDeliveryFeeForKMs) {
                $differenceInDistance = $distance - $distanceOfFixedDeliveryFeeForKMs;
                $deliveryFee = round($deliveryFee + ($extraDeliveryFeePerKm * $differenceInDistance));
            }
        }

        return [$deliveryFee, $distance];
    }

    public function calculateJetDeliveryFee(
        $totalAmount,
        $latitude = null,
        $longitude = null
    ): float {

        $fixedDeliveryFee = $this->jet_fixed_delivery_fee;
        $commission_rate = $this->jet_delivery_commission_rate;
        $extraDeliveryFeePerKm = $this->jet_extra_delivery_fee_per_km;
        $fixedDeliveryDistanceKeyName = 'restaurant_fixed_delivery_distance';

        $deliveryFee = $fixedDeliveryFee;
        if ($commission_rate > 0) {
            $deliveryFee += ($totalAmount * $commission_rate) / 100;
        }

        if ($this->jet_extra_delivery_fee_per_km > 0 && ! is_null($latitude) && ! is_null($longitude)) {
            $distance = Controller::distanceBetween($this->latitude, $this->longitude, $latitude,
                $longitude);
            if ($distance != 0 && Preference::retrieveValue($fixedDeliveryDistanceKeyName) && $distance > Preference::retrieveValue($fixedDeliveryDistanceKeyName)) {
                $deliveryFee += $extraDeliveryFeePerKm * $distance;
            }
        }

        return $deliveryFee ?? 0;
    }
}
