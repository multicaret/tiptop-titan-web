<?php

namespace App\Models\OldModels;


use App\Models\Chain;
use Astrotomic\Translatable\Translatable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\OldModels\OldChain
 *
 * @property int $id
 * @property string $currency
 * @property string $status
 * @property int|null $added_by
 * @property string $app_percentage
 * @property string|null $delivery_app_percentage
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $zoho_id
 * @property-read Collection|OldBranch[] $branches
 * @property-read int|null $branches_count
 * @property-read Collection|OldProduct[] $products
 * @property-read int|null $products_count
 * @property-read OldChainTranslation|null $translation
 * @property-read Collection|OldChainTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|OldChain listsTranslations(string $translationField)
 * @method static Builder|OldChain newModelQuery()
 * @method static Builder|OldChain newQuery()
 * @method static Builder|OldChain notTranslatedIn(?string $locale = null)
 * @method static Builder|OldChain orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|OldChain orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|OldChain orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|OldChain query()
 * @method static Builder|OldChain translated()
 * @method static Builder|OldChain translatedIn(?string $locale = null)
 * @method static Builder|OldChain whereAddedBy($value)
 * @method static Builder|OldChain whereAppPercentage($value)
 * @method static Builder|OldChain whereCreatedAt($value)
 * @method static Builder|OldChain whereCurrency($value)
 * @method static Builder|OldChain whereDeletedAt($value)
 * @method static Builder|OldChain whereDeliveryAppPercentage($value)
 * @method static Builder|OldChain whereId($value)
 * @method static Builder|OldChain whereStatus($value)
 * @method static Builder|OldChain whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|OldChain whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|OldChain whereUpdatedAt($value)
 * @method static Builder|OldChain whereZohoId($value)
 * @method static Builder|OldChain withTranslation()
 * @mixin Eloquent
 */
class OldChain extends OldModel
{
    use Translatable;

    protected $table = 'jo3aan_restaurants';
    protected $with = ['translations'];
    protected $translationForeignKey = 'restaurant_id';
    protected array $translatedAttributes = ['title', 'description'];

    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_DISABLED = 'DISABLED';
    public const STATUS_SUSPENDED = 'SUSPENDED';


    public static function attributesComparing(): array
    {
        return [
            'id' => 'id',
            'delivery_app_percentage' => 'tiptop_delivery_app_percentage',
            'app_percentage' => 'restaurant_app_percentage',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
        ];
    }

    public function branches()
    {
        return $this->hasMany(OldBranch::class, 'restaurant_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(OldProduct::class, 'restaurant_id');
    }

    public static function statusesComparing(): array
    {
        return [
            self::STATUS_ACTIVE => Chain::STATUS_ACTIVE,
            self::STATUS_DISABLED => Chain::STATUS_DRAFT,
            self::STATUS_SUSPENDED => Chain::STATUS_INACTIVE,
        ];
    }
}
