<?php

namespace App\Models\OldModels;


use App\Models\Branch;
use Astrotomic\Translatable\Translatable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\OldModels\OldBranch
 *
 * @property int $id
 * @property int $restaurant_id
 * @property int|null $delivery_service
 * @property int|null $app_delivery_service
 * @property int|null $app_delivery_customer_appearing
 * @property int|null $internet_service
 * @property int|null $accept_bankcard
 * @property int|null $reservation_service
 * @property int|null $smoking_zone
 * @property int|null $air_conditioning
 * @property int|null $delivery_time
 * @property int|null $app_delivery_time
 * @property int|null $delivery_fee
 * @property int|null $app_delivery_fee
 * @property int|null $maximum_order_capacity
 * @property int|null $minimun_order
 * @property int|null $app_minimun_order
 * @property string $status DISABLED,OPEN,CLOSED,SUSPENDED
 * @property int $online
 * @property int|null $is_grocery
 * @property int|null $added_by
 * @property string|null $phone_number
 * @property int|null $neighborhood_id
 * @property int|null $municipality_id
 * @property int|null $country_id
 * @property int|null $city_id
 * @property string|null $address
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $price_per_person_low
 * @property string|null $price_per_person_high
 * @property string|null $covered_area_diameter
 * @property string $rating
 * @property int $rating_count
 * @property string|null $contact_name
 * @property string|null $contact_phone_1
 * @property string|null $contact_phone_2
 * @property string|null $contact_email
 * @property string|null $conclusion
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $workiom_id
 * @property string|null $zoho_id
 * @property string|null $zoho_books_id
 * @property string|null $zoho_delivery_item_id
 * @property string|null $zoho_tiptop_delivery_item_id
 * @property-read Collection|OldCategory[] $categories
 * @property-read int|null $categories_count
 * @property-read OldChain $oldChain
 * @property-read OldBranchTranslation|null $translation
 * @property-read Collection|OldBranchTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|OldBranch listsTranslations(string $translationField)
 * @method static Builder|OldBranch newModelQuery()
 * @method static Builder|OldBranch newQuery()
 * @method static Builder|OldBranch notTranslatedIn(?string $locale = null)
 * @method static Builder|OldBranch orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|OldBranch orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|OldBranch orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|OldBranch query()
 * @method static Builder|OldBranch translated()
 * @method static Builder|OldBranch translatedIn(?string $locale = null)
 * @method static Builder|OldBranch whereAcceptBankcard($value)
 * @method static Builder|OldBranch whereAddedBy($value)
 * @method static Builder|OldBranch whereAddress($value)
 * @method static Builder|OldBranch whereAirConditioning($value)
 * @method static Builder|OldBranch whereAppDeliveryCustomerAppearing($value)
 * @method static Builder|OldBranch whereAppDeliveryFee($value)
 * @method static Builder|OldBranch whereAppDeliveryService($value)
 * @method static Builder|OldBranch whereAppDeliveryTime($value)
 * @method static Builder|OldBranch whereAppMinimunOrder($value)
 * @method static Builder|OldBranch whereCityId($value)
 * @method static Builder|OldBranch whereConclusion($value)
 * @method static Builder|OldBranch whereContactEmail($value)
 * @method static Builder|OldBranch whereContactName($value)
 * @method static Builder|OldBranch whereContactPhone1($value)
 * @method static Builder|OldBranch whereContactPhone2($value)
 * @method static Builder|OldBranch whereCountryId($value)
 * @method static Builder|OldBranch whereCoveredAreaDiameter($value)
 * @method static Builder|OldBranch whereCreatedAt($value)
 * @method static Builder|OldBranch whereDeletedAt($value)
 * @method static Builder|OldBranch whereDeliveryFee($value)
 * @method static Builder|OldBranch whereDeliveryService($value)
 * @method static Builder|OldBranch whereDeliveryTime($value)
 * @method static Builder|OldBranch whereId($value)
 * @method static Builder|OldBranch whereInternetService($value)
 * @method static Builder|OldBranch whereIsGrocery($value)
 * @method static Builder|OldBranch whereLatitude($value)
 * @method static Builder|OldBranch whereLongitude($value)
 * @method static Builder|OldBranch whereMaximumOrderCapacity($value)
 * @method static Builder|OldBranch whereMinimunOrder($value)
 * @method static Builder|OldBranch whereMunicipalityId($value)
 * @method static Builder|OldBranch whereNeighborhoodId($value)
 * @method static Builder|OldBranch whereOnline($value)
 * @method static Builder|OldBranch wherePhoneNumber($value)
 * @method static Builder|OldBranch wherePricePerPersonHigh($value)
 * @method static Builder|OldBranch wherePricePerPersonLow($value)
 * @method static Builder|OldBranch whereRating($value)
 * @method static Builder|OldBranch whereRatingCount($value)
 * @method static Builder|OldBranch whereReservationService($value)
 * @method static Builder|OldBranch whereRestaurantId($value)
 * @method static Builder|OldBranch whereSmokingZone($value)
 * @method static Builder|OldBranch whereStatus($value)
 * @method static Builder|OldBranch whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|OldBranch whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|OldBranch whereUpdatedAt($value)
 * @method static Builder|OldBranch whereWorkiomId($value)
 * @method static Builder|OldBranch whereZohoBooksId($value)
 * @method static Builder|OldBranch whereZohoDeliveryItemId($value)
 * @method static Builder|OldBranch whereZohoId($value)
 * @method static Builder|OldBranch whereZohoTiptopDeliveryItemId($value)
 * @method static Builder|OldBranch withTranslation()
 * @mixin Eloquent
 * @property string|null $zoho_books_account_id
 * @method static Builder|OldBranch whereZohoBooksAccountId($value)
 */
class OldBranch extends OldModel
{
    use Translatable;

    protected $table = 'jo3aan_branches';
    protected $primaryKey = 'id';
    protected $with = ['translations', 'categories'];
    protected $translationForeignKey = 'branch_id';
    protected array $translatedAttributes = ['title_suffex', 'description'];

    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_DISABLED = 'DISABLED';
    public const STATUS_SUSPENDED = 'SUSPENDED';

    public function attributesComparing($type): array
    {
        $attributesKeys = [
            'id' => 'id',
            'app_minimun_order' => 'minimum_order',
            'rating' => 'avg_rating',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'contact_phone_1' => 'primary_phone_number',
            'contact_phone_2' => 'secondary_phone_number',
        ];

        if ($type === Branch::CHANNEL_GROCERY_OBJECT) {
            $attributesKeys = array_merge($attributesKeys, ['delivery_fee' => 'fixed_delivery_fee']);
        }
        if ($type === Branch::CHANNEL_FOOD_OBJECT) {
            $attributesKeys = array_merge($attributesKeys, [
                'app_delivery_fee' => 'fixed_delivery_fee',
                'delivery_fee' => 'restaurant_fixed_delivery_fee',
                'delivery_time' => 'min_delivery_minutes',
                'app_delivery_time' => 'restaurant_min_delivery_minutes',
            ]);
        }

        if (self::validateLatLong($this->latitude, $this->longitude)) {
            $attributesKeys = array_merge($attributesKeys, [
                'latitude' => 'latitude',
                'longitude' => 'longitude'
            ]);
        }

        return $attributesKeys;
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(OldCategory::class, 'jo3aan_branches_categories', 'branch_id', 'category_id')
                    ->where('type', '!=', OldCategory::TYPE_KITCHENS);
    }

    public static function statusesComparing(): array
    {
        return [
            self::STATUS_ACTIVE => Branch::STATUS_ACTIVE,
            self::STATUS_DISABLED => Branch::STATUS_DRAFT,
            self::STATUS_SUSPENDED => Branch::STATUS_INACTIVE,
        ];
    }

    public function oldChain(): BelongsTo
    {
        return $this->belongsTo(OldChain::class, 'restaurant_id');
    }
}
