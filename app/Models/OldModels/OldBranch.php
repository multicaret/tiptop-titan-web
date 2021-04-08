<?php

namespace App\Models\OldModels;


use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $workiom_id
 * @property string|null $zoho_id
 * @property string|null $zoho_books_id
 * @property string|null $zoho_delivery_item_id
 * @property string|null $zoho_tiptop_delivery_item_id
 * @property-read \App\Models\OldModels\OldBranchTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OldModels\OldBranchTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch query()
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch translated()
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereAcceptBankcard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereAirConditioning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereAppDeliveryCustomerAppearing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereAppDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereAppDeliveryService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereAppDeliveryTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereAppMinimunOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereConclusion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereContactEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereContactPhone1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereContactPhone2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereCoveredAreaDiameter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereDeliveryService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereDeliveryTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereInternetService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereIsGrocery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereMaximumOrderCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereMinimunOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereMunicipalityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereNeighborhoodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch wherePricePerPersonHigh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch wherePricePerPersonLow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereReservationService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereSmokingZone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereWorkiomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereZohoBooksId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereZohoDeliveryItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereZohoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch whereZohoTiptopDeliveryItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranch withTranslation()
 * @mixin \Eloquent
 */
class OldBranch extends Model
{
    use Translatable;

    protected $connection = 'mysql-old';
    protected $table = 'jo3aan_branches';
    protected $primaryKey = 'id';
    protected $with = ['translations'];
    protected $translationForeignKey = 'branch_id';
    protected array $translatedAttributes = ['title_suffex', 'description'];

    protected static function booted()
    {
        static::addGlobalScope('not-ancient', function (Builder $builder) {
            $beginsAt = Carbon::parse('2020-12-25')->setTimeFromTimeString('00:00');
            $builder->where('created_at', '>=', $beginsAt);
        });
    }

    public static function attributesComparing(): array
    {
        return [
            'id' => 'id',
            'app_minimun_order' => 'minimum_order',
            'delivery_fee' => 'fixed_delivery_fee',
            'latitude' => 'latitude',
            'longitude' => 'longitude',
            'rating' => 'avg_rating',
            'rating_count' => 'rating_count',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'contact_phone_1' => 'primary_phone_number',
            'contact_phone_2' => 'secondary_phone_number',
        ];
    }
}
