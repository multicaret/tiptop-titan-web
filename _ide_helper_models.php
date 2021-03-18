<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Activity
 *
 * @property int $id
 * @property int $user_id
 * @property string $subject_type
 * @property int $subject_id
 * @property string $type
 * @property int $is_private
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $subject
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Activity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Activity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Activity query()
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereIsPrivate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereSubjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereUserId($value)
 */
	class Activity extends \Eloquent {}
}

namespace App\Models{
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
 */
	class Barcode extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * App\Models\BarcodeProduct
 *
 * @property int $id
 * @property int $barcode_id
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BarcodeProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BarcodeProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BarcodeProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|BarcodeProduct whereBarcodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BarcodeProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BarcodeProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BarcodeProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BarcodeProduct whereUpdatedAt($value)
 */
	class BarcodeProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Basket
 *
 * @property int $id
 * @property int $user_id
 * @property int $chain_id
 * @property int $branch_id
 * @property-read int|null $products_count
 * @property int|null $crm_id
 * @property int|null $crm_user_id
 * @property int $status 0:In Progress, 1: Completed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BasketProduct[] $basketProducts
 * @property-read int|null $basket_products_count
 * @property-read \App\Models\Branch $branch
 * @property-read \App\Models\Chain $chain
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Basket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Basket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Basket query()
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereChainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereCrmId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereCrmUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereProductsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereUserId($value)
 */
	class Basket extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BasketProduct
 *
 * @property int $id
 * @property int $basket_id
 * @property int $product_id
 * @property int $quantity
 * @property array $product_object
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Basket $basket
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|BasketProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BasketProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BasketProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|BasketProduct whereBasketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasketProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasketProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasketProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasketProduct whereProductObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasketProduct whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasketProduct whereUpdatedAt($value)
 */
	class BasketProduct extends \Eloquent {}
}

namespace App\Models{
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
 * @property float $minimum_order
 * @property float $under_minimum_order_delivery_fee
 * @property float $fixed_delivery_fee
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
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Chain $chain
 * @property-read mixed $is_published
 * @property-read mixed $status_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $managers
 * @property-read int|null $managers_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $supervisors
 * @property-read int|null $supervisors_count
 * @property-read \App\Models\BranchTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BranchTranslation[] $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WorkingHour[] $workingHours
 * @property-read int|null $working_hours_count
 * @method static \Illuminate\Database\Eloquent\Builder|Branch draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Branch published()
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
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereMinimumOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch wherePrimaryPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereRegionId($value)
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
 */
	class Branch extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * App\Models\BranchManager
 *
 * @property int $id
 * @property int $branch_id
 * @property int $manager_id
 * @property int $is_primary
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BranchManager newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchManager newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchManager query()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchManager whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchManager whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchManager whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchManager whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchManager whereManagerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchManager whereUpdatedAt($value)
 */
	class BranchManager extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BranchSupervisor
 *
 * @property int $id
 * @property int $branch_id
 * @property int $supervisor_id
 * @property int $is_primary
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BranchSupervisor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchSupervisor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchSupervisor query()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchSupervisor whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchSupervisor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchSupervisor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchSupervisor whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchSupervisor whereSupervisorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchSupervisor whereUpdatedAt($value)
 */
	class BranchSupervisor extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BranchTranslation
 *
 * @property int $id
 * @property int $branch_id
 * @property string $title
 * @property string|null $description
 * @property string $locale
 * @method static \Illuminate\Database\Eloquent\Builder|BranchTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchTranslation whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchTranslation whereTitle($value)
 */
	class BranchTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CategoryProduct
 *
 * @property int $id
 * @property int $category_id
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProduct whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProduct whereUpdatedAt($value)
 */
	class CategoryProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CategoryProductCrossSell
 *
 * @property int $id
 * @property int $category_id
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Taxonomy $category
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductCrossSell newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductCrossSell newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductCrossSell query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductCrossSell whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductCrossSell whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductCrossSell whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductCrossSell whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductCrossSell whereUpdatedAt($value)
 */
	class CategoryProductCrossSell extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CategoryProductUpSell
 *
 * @property int $id
 * @property int $category_id
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Taxonomy $category
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductUpSell newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductUpSell newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductUpSell query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductUpSell whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductUpSell whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductUpSell whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductUpSell whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductUpSell whereUpdatedAt($value)
 */
	class CategoryProductUpSell extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Chain
 *
 * @property int $id
 * @property string $uuid
 * @property int $creator_id
 * @property int $editor_id
 * @property int|null $region_id
 * @property int|null $city_id
 * @property int|null $currency_id
 * @property int $type 1:Market, 2: Food
 * @property string|null $primary_phone_number
 * @property string|null $secondary_phone_number
 * @property string|null $whatsapp_phone_number
 * @property string $primary_color
 * @property string $secondary_color
 * @property int $number_of_items_on_mobile_grid_view
 * @property string $avg_rating
 * @property int $rating_count
 * @property int $view_count
 * @property int|null $order_column
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Branch[] $branches
 * @property-read int|null $branches_count
 * @property-read mixed $cover
 * @property-read mixed $gallery
 * @property-read mixed $is_published
 * @property-read bool $logo
 * @property-read mixed $status_name
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \App\Models\ChainTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ChainTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Chain draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Chain inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Chain incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|Chain listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chain newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chain notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|Chain notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Chain published()
 * @method static \Illuminate\Database\Eloquent\Builder|Chain query()
 * @method static \Illuminate\Database\Eloquent\Builder|Chain translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Chain translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereAvgRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereNumberOfItemsOnMobileGridView($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain wherePrimaryColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain wherePrimaryPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereSecondaryColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereSecondaryPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereViewCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereWhatsappPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain withTranslation()
 */
	class Chain extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * App\Models\ChainTranslation
 *
 * @property int $id
 * @property int $chain_id
 * @property string $title
 * @property string|null $description
 * @property string $locale
 * @method static \Illuminate\Database\Eloquent\Builder|ChainTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChainTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChainTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChainTranslation whereChainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChainTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChainTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChainTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChainTranslation whereTitle($value)
 */
	class ChainTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\City
 *
 * @property int $id
 * @property int $country_id
 * @property int|null $region_id
 * @property int|null $timezone_id
 * @property string $english_name
 * @property int|null $population
 * @property string|null $latitude
 * @property string|null $longitude
 * @property int|null $order_column
 * @property int $status 0:incomplete, 1:draft, 2:published, 3:Inactive, 4..n:CUSTOM
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Country $country
 * @property-read mixed $cover
 * @property-read mixed $gallery
 * @property-read mixed $is_published
 * @property-read mixed $status_name
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Region|null $region
 * @property-read \App\Models\Timezone|null $timezone
 * @property-read \App\Models\CityTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CityTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|City draft()
 * @method static \Illuminate\Database\Eloquent\Builder|City inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|City incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|City listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|City notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|City orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|City orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|City orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|City published()
 * @method static \Illuminate\Database\Eloquent\Builder|City query()
 * @method static \Illuminate\Database\Eloquent\Builder|City translated()
 * @method static \Illuminate\Database\Eloquent\Builder|City translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereEnglishName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City wherePopulation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereTimezoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|City whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City withTranslation()
 */
	class City extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * App\Models\CityTranslation
 *
 * @property int $id
 * @property int $city_id
 * @property string $name
 * @property string|null $description
 * @property string $locale
 * @property string|null $slug
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereSlug($value)
 */
	class CityTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Comment
 *
 * @property int $id
 * @property int|null $parent_id
 * @property int $user_id
 * @property int $type 1:Comment, 2: Review, 3..n: CUSTOM
 * @property string|null $content
 * @property string $commentable_type
 * @property int $commentable_id
 * @property int $left
 * @property int $right
 * @property int|null $depth
 * @property int $votes
 * @property int $status 1:shown, 2:reported
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Comment[] $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $commentable
 * @property-read Comment|null $parent
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Node limitDepth($limit)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Query\Builder|Comment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereRight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereVotes($value)
 * @method static \Illuminate\Database\Query\Builder|Comment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Node withoutNode($node)
 * @method static \Illuminate\Database\Eloquent\Builder|Node withoutRoot()
 * @method static \Illuminate\Database\Eloquent\Builder|Node withoutSelf()
 * @method static \Illuminate\Database\Query\Builder|Comment withoutTrashed()
 */
	class Comment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Country
 *
 * @property int $id
 * @property int|null $currency_id
 * @property int|null $language_id
 * @property int|null $timezone_id
 * @property string $english_name
 * @property string $alpha2_code
 * @property string $alpha3_code
 * @property int $numeric_code
 * @property string|null $phone_code
 * @property int|null $order_column
 * @property int $status 0:incomplete, 1:draft, 2:published, 3:Inactive, 4..n:CUSTOM
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $cities
 * @property-read int|null $cities_count
 * @property-read \App\Models\Currency|null $currency
 * @property-read mixed $is_published
 * @property-read mixed $status_name
 * @property-read \App\Models\Language|null $language
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Location[] $locations
 * @property-read int|null $locations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Region[] $regions
 * @property-read int|null $regions_count
 * @property-read \App\Models\Timezone|null $timezone
 * @property-read \App\Models\CountryTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CountryTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Country draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Country inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Country incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|Country listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|Country notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Country orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Country orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Country orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Country published()
 * @method static \Illuminate\Database\Eloquent\Builder|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder|Country translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Country translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereAlpha2Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereAlpha3Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereEnglishName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereNumericCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country wherePhoneCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereTimezoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country withTranslation()
 */
	class Country extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CountryTranslation
 *
 * @property int $id
 * @property int $country_id
 * @property string $name
 * @property string $locale
 * @property string|null $slug
 * @method static \Illuminate\Database\Eloquent\Builder|CountryTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CountryTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CountryTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|CountryTranslation whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CountryTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CountryTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CountryTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CountryTranslation whereSlug($value)
 */
	class CountryTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Coupon
 *
 * @property int $id
 * @property int $creator_id
 * @property int $editor_id
 * @property int $currency_id
 * @property int|null $discount_by_percentage true: percentage, false: fixed amount
 * @property float|null $discount_amount
 * @property int|null $total_usage_count
 * @property int $usage_count_by_same_user
 * @property string|null $expired_at
 * @property string $code
 * @property int $status 0:incomplete, 1:draft, 2:published, 3:Inactive, 4..n:CUSTOM
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon query()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDiscountByPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereTotalUsageCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereUsageCountBySameUser($value)
 */
	class Coupon extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CouponInstance
 *
 * @property int $id
 * @property int $coupon_id
 * @property int $redeemer_id
 * @property int $redeemed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Coupon $coupon
 * @property-read \App\Models\User $redeemer
 * @method static \Illuminate\Database\Eloquent\Builder|CouponInstance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponInstance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponInstance query()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponInstance whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponInstance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponInstance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponInstance whereRedeemedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponInstance whereRedeemerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponInstance whereUpdatedAt($value)
 */
	class CouponInstance extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Currency
 *
 * @property int $id
 * @property string $english_name
 * @property string $code
 * @property string $symbol
 * @property float|null $rate
 * @property string|null $decimal_separator
 * @property string|null $thousands_separator
 * @property bool|null $is_symbol_after
 * @property int $status 0:incomplete, 1:draft, 2:published, 3:Inactive, 4..n:CUSTOM
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Country[] $countries
 * @property-read int|null $countries_count
 * @property-read mixed $is_published
 * @property-read mixed $status_name
 * @method static \Illuminate\Database\Eloquent\Builder|Currency draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency published()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency query()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereDecimalSeparator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereEnglishName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereIsSymbolAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereThousandsSeparator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereUpdatedAt($value)
 */
	class Currency extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Language
 *
 * @property int $id
 * @property string $english_name
 * @property string $code
 * @property string $locale_country
 * @property bool $is_rtl
 * @property int $status 0:incomplete, 1:draft, 2:published, 3:Inactive, 4..n:CUSTOM
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Country[] $countries
 * @property-read int|null $countries_count
 * @property-read mixed $is_published
 * @property-read mixed $status_name
 * @property-read \App\Models\LanguageTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LanguageTranslation[] $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Language draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Language inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Language incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|Language listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Language newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Language newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Language notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|Language notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Language orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Language orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Language orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Language published()
 * @method static \Illuminate\Database\Eloquent\Builder|Language query()
 * @method static \Illuminate\Database\Eloquent\Builder|Language translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Language translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereEnglishName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereIsRtl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereLocaleCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language withTranslation()
 */
	class Language extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LanguageTranslation
 *
 * @property int $id
 * @property int $language_id
 * @property string $name
 * @property string $locale
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageTranslation whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageTranslation whereName($value)
 */
	class LanguageTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Location
 *
 * @property int $id
 * @property int $creator_id
 * @property int $editor_id
 * @property string|null $contactable_type
 * @property int|null $contactable_id
 * @property int|null $country_id
 * @property int|null $region_id
 * @property int|null $city_id
 * @property string|null $alias
 * @property string|null $name
 * @property string|null $address1
 * @property string|null $address2
 * @property string|null $building
 * @property string|null $floor
 * @property string|null $apartment
 * @property string|null $postcode
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $notes
 * @property object|null $phones
 * @property string|null $mobiles
 * @property object|null $emails
 * @property string|null $social_media
 * @property string|null $website
 * @property string|null $position
 * @property string|null $company
 * @property string|null $vat value added tax
 * @property string|null $vat_office
 * @property bool $is_default
 * @property int $type 1: Address, 2: Contact
 * @property int $kind 1: Home, 2: Work, 3:Other
 * @property int $status 0:incomplete, 1:draft, 2:published, 3:Inactive, 4..n:CUSTOM
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\City|null $city
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $contactable
 * @property-read \App\Models\Country|null $country
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\User $editor
 * @property-read mixed $is_published
 * @property-read mixed $status_name
 * @property-read \App\Models\Region|null $region
 * @method static \Illuminate\Database\Eloquent\Builder|Location addresses()
 * @method static \Illuminate\Database\Eloquent\Builder|Location contacts()
 * @method static \Illuminate\Database\Eloquent\Builder|Location draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Location inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Location incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location notPublished()
 * @method static \Illuminate\Database\Query\Builder|Location onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Location published()
 * @method static \Illuminate\Database\Eloquent\Builder|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereAddress1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereApartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereBuilding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereContactableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereContactableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereEmails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereFloor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereKind($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereMobiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location wherePhones($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location wherePostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereSocialMedia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereVatOffice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereWebsite($value)
 * @method static \Illuminate\Database\Query\Builder|Location withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Location withoutTrashed()
 */
	class Location extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Manufacturer
 *
 * @property int $id
 * @property int $creator_id
 * @property int $editor_id
 * @property int|null $region_id
 * @property int|null $city_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $is_published
 * @property-read bool $logo
 * @property-read mixed $status_name
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer published()
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer whereUpdatedAt($value)
 */
	class Manufacturer extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * App\Models\Media
 *
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property string|null $uuid
 * @property string $collection_name
 * @property string $name
 * @property string $file_name
 * @property string|null $mime_type
 * @property string $disk
 * @property string|null $conversions_disk
 * @property int $size
 * @property array $manipulations
 * @property array $custom_properties
 * @property array $generated_conversions
 * @property array $responsive_images
 * @property int|null $order_column
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $extension
 * @property-read string $human_readable_size
 * @property-read string $type
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $model
 * @method static \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|static[] all($columns = ['*'])
 * @method static \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|static[] get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|Media query()
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereCollectionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereConversionsDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereCustomProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereGeneratedConversions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereManipulations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereResponsiveImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereUuid($value)
 */
	class Media extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MetaData
 *
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\MetaDataTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MetaDataTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData query()
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData translated()
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData withTranslation()
 */
	class MetaData extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MetaDataTranslation
 *
 * @property int $id
 * @property int $meta_data_id
 * @property string $locale
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $og_title
 * @property string|null $og_description
 * @property string|null $og_type
 * @property string|null $twitter_card
 * @property string|null $twitter_title
 * @property string|null $twitter_description
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation whereMetaDataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation whereOgDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation whereOgTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation whereOgType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation whereTwitterCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation whereTwitterDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation whereTwitterTitle($value)
 */
	class MetaDataTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Notification
 *
 * @property string $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $notifiable
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection|static[] all($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection|static[] get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder|DatabaseNotification read()
 * @method static \Illuminate\Database\Eloquent\Builder|DatabaseNotification unread()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUpdatedAt($value)
 */
	class Notification extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $reference_code
 * @property int $user_id
 * @property int $chain_id
 * @property int $branch_id
 * @property int $basket_id
 * @property int $payment_method_id
 * @property int $address_id
 * @property int|null $coupon_id
 * @property int|null $previous_order_id
 * @property float $total
 * @property float $coupon_discount_amount
 * @property float $delivery_fee
 * @property float $grand_total
 * @property float $private_payment_method_commission
 * @property float $private_total
 * @property float $private_delivery_fee
 * @property float $private_grand_total
 * @property string $avg_rating
 * @property int $rating_count
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property string|null $notes
 * @property int $status 
 *             0: Cancelled,
 *             1: Draft,
 *             6: Waiting Courier,
 *             10: Preparing,
 *             16: On the way,
 *             18: At the address,
 *             20: Delivered,
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Location $address
 * @property-read \App\Models\Basket $basket
 * @property-read \App\Models\Branch $branch
 * @property-read \App\Models\Chain $chain
 * @property-read \App\Models\Coupon|null $coupon
 * @property-read \App\Models\PaymentMethod $paymentMethod
 * @property-read Order|null $previousOrder
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Query\Builder|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAvgRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereBasketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereChainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCouponDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePreviousOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrivateDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrivateGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrivatePaymentMethodCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrivateTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereReferenceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Order withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Order withoutTrashed()
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\OrderProduct
 *
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct query()
 */
	class OrderProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PaymentMethod
 *
 * @property int $id
 * @property int $creator_id
 * @property int $editor_id
 * @property int $status 0:incomplete, 1:draft, 2:published, 3:Inactive, 4..n:CUSTOM
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\User $editor
 * @property-read mixed $is_published
 * @property-read bool $logo
 * @property-read mixed $status_name
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \App\Models\PaymentMethodTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PaymentMethodTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod draft()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Query\Builder|PaymentMethod onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod published()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod translated()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod translatedIn(?string $locale = null)
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
 * @method static \Illuminate\Database\Query\Builder|PaymentMethod withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PaymentMethod withoutTrashed()
 */
	class PaymentMethod extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * App\Models\PaymentMethodTranslation
 *
 * @property int $id
 * @property int $payment_method_id
 * @property string|null $title
 * @property string|null $description
 * @property mixed|null $instructions
 * @property string $locale
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethodTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethodTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethodTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethodTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethodTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethodTranslation whereInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethodTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethodTranslation wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethodTranslation whereTitle($value)
 */
	class PaymentMethodTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Post
 *
 * @property int $id
 * @property string $uuid
 * @property int $creator_id
 * @property int $editor_id
 * @property int|null $category_id
 * @property int $type 1:Article, 2:Page, 3:Testimonial, 4..n: CUSTOM
 * @property string $avg_rating
 * @property int $rating_count
 * @property int $view_count
 * @property int|null $order_column
 * @property int $status 0:incomplete, 1:draft, 2:published, 3:Inactive, 4..n:CUSTOM
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Activity[] $activity
 * @property-read int|null $activity_count
 * @property-read \App\Models\Taxonomy|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\User $editor
 * @property-read mixed $cover
 * @property-read mixed $gallery
 * @property-read mixed $is_published
 * @property-read mixed $link
 * @property-read mixed $status_name
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \App\Models\MetaData $meta
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $ratings
 * @property-read int|null $ratings_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Taxonomy[] $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\PostTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PostTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Post articles()
 * @method static \Illuminate\Database\Eloquent\Builder|Post blog()
 * @method static \Illuminate\Database\Eloquent\Builder|Post companiesTestimonials()
 * @method static \Illuminate\Database\Eloquent\Builder|Post draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Post faq()
 * @method static \Illuminate\Database\Eloquent\Builder|Post inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Post incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|Post listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post news()
 * @method static \Illuminate\Database\Eloquent\Builder|Post notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|Post notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Query\Builder|Post onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Post orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Post pages()
 * @method static \Illuminate\Database\Eloquent\Builder|Post portfolios()
 * @method static \Illuminate\Database\Eloquent\Builder|Post published()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post services()
 * @method static \Illuminate\Database\Eloquent\Builder|Post translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Post translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post usersTestimonials()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereAvgRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereViewCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post withTranslation()
 * @method static \Illuminate\Database\Query\Builder|Post withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Post withoutTrashed()
 */
	class Post extends \Eloquent implements \Spatie\MediaLibrary\HasMedia, \App\Contracts\ShouldHaveTypes {}
}

namespace App\Models{
/**
 * App\Models\PostTaxonomy
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PostTaxonomy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostTaxonomy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostTaxonomy query()
 */
	class PostTaxonomy extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PostTranslation
 *
 * @property int $id
 * @property int $post_id
 * @property string $locale
 * @property string $title
 * @property string|null $content
 * @property string|null $excerpt
 * @property string|null $notes
 * @method static \Illuminate\Database\Eloquent\Builder|PostTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|PostTranslation whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostTranslation whereExcerpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostTranslation whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostTranslation wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostTranslation whereTitle($value)
 */
	class PostTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Preference
 *
 * @property int $id
 * @property string $key
 * @property string $type
 * @property string|null $notes
 * @property string|null $group_name
 * @property int|null $order_column
 * @property string|null $icon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \App\Models\PreferenceTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PreferenceTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Preference listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Preference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Preference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Preference notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Preference orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Preference orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Preference orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Preference query()
 * @method static \Illuminate\Database\Eloquent\Builder|Preference sections()
 * @method static \Illuminate\Database\Eloquent\Builder|Preference translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Preference translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Preference whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preference whereGroupName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preference whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preference whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preference whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preference whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preference whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preference whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Preference whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Preference whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preference whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preference withTranslation()
 */
	class Preference extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * App\Models\PreferenceTranslation
 *
 * @property int $id
 * @property int $preference_id
 * @property string $locale
 * @property string|null $value
 * @method static \Illuminate\Database\Eloquent\Builder|PreferenceTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PreferenceTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PreferenceTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|PreferenceTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreferenceTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreferenceTranslation wherePreferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreferenceTranslation whereValue($value)
 */
	class PreferenceTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $uuid
 * @property int $creator_id
 * @property int $editor_id
 * @property int $chain_id
 * @property int $branch_id
 * @property int $category_id
 * @property int|null $unit_id
 * @property float|null $price
 * @property float|null $price_discount_amount
 * @property bool|null $price_discount_by_percentage true: percentage, false: fixed amount
 * @property int|null $quantity
 * @property string|null $sku
 * @property int|null $upc
 * @property int|null $is_storage_tracking_enabled
 * @property float|null $width x
 * @property float|null $height y
 * @property float|null $depth z
 * @property float|null $weight w
 * @property int|null $minimum_orderable_quantity
 * @property int|null $order_column
 * @property string $avg_rating
 * @property int $rating_count
 * @property int $view_count
 * @property int|null $status
 * @property int|null $price_discount_began_at
 * @property int|null $price_discount_finished_at
 * @property int|null $custom_banner_began_at
 * @property int|null $custom_banner_ended_at
 * @property int $on_mobile_grid_tile_weight
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Barcode[] $barcodes
 * @property-read int|null $barcodes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Basket[] $baskets
 * @property-read int|null $baskets_count
 * @property-read \App\Models\Branch $branch
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Taxonomy[] $categories
 * @property-read int|null $categories_count
 * @property-read \App\Models\Chain $chain
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\User $editor
 * @property-read mixed $cover
 * @property-read mixed $cover_full
 * @property-read mixed $discounted_price
 * @property-read string $discounted_price_formatted
 * @property-read mixed $gallery
 * @property-read mixed $is_published
 * @property-read mixed $price_formatted
 * @property-read mixed $status_name
 * @property-read mixed $thumbnail
 * @property-read \App\Models\Taxonomy $masterCategory
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Taxonomy[] $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\ProductTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductTranslation[] $translations
 * @property-read int|null $translations_count
 * @property-read \App\Models\Taxonomy|null $unit
 * @method static \Illuminate\Database\Eloquent\Builder|Product draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Product forCategory($categoryId)
 * @method static \Illuminate\Database\Eloquent\Builder|Product inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Product incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|Product listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|Product notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Query\Builder|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Product orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Product orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Product published()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Product translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAvgRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereChainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCustomBannerBeganAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCustomBannerEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsStorageTrackingEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMinimumOrderableQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereOnMobileGridTileWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePriceDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePriceDiscountBeganAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePriceDiscountByPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePriceDiscountFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereViewCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product withTranslation()
 * @method static \Illuminate\Database\Query\Builder|Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Product withoutTrashed()
 */
	class Product extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * App\Models\ProductTag
 *
 * @property int $id
 * @property int $product_id
 * @property int $tag_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTag query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTag whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTag whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTag whereUpdatedAt($value)
 */
	class ProductTag extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductTranslation
 *
 * @property int $id
 * @property int $product_id
 * @property string $locale
 * @property string $title
 * @property string|null $description
 * @property string|null $excerpt
 * @property string|null $notes
 * @property string|null $custom_banner_text
 * @property string|null $unit_text
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereCustomBannerText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereExcerpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereUnitText($value)
 */
	class ProductTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\QrCode
 *
 * @property int $id
 * @property string $qr_codeable_type
 * @property int $qr_codeable_id
 * @property string $route
 * @property array|null $route_params
 * @property int $is_external_route
 * @property string $forecolor
 * @property string $backcolor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $qrCodeable
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereBackcolor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereForecolor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereIsExternalRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereQrCodeableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereQrCodeableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereRouteParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereUpdatedAt($value)
 */
	class QrCode extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Region
 *
 * @property int $id
 * @property int $country_id
 * @property string $english_name
 * @property string|null $code
 * @property int|null $order_column
 * @property int $status 0:incomplete, 1:draft, 2:published, 3:Inactive, 4..n:CUSTOM
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $cities
 * @property-read int|null $cities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Location[] $contacts
 * @property-read int|null $contacts_count
 * @property-read \App\Models\Country $country
 * @property-read mixed $cover
 * @property-read mixed $gallery
 * @property-read mixed $is_published
 * @property-read mixed $status_name
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \App\Models\RegionTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RegionTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Region draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Region inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Region incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|Region listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|Region notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Region orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Region orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Region orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Region published()
 * @method static \Illuminate\Database\Eloquent\Builder|Region query()
 * @method static \Illuminate\Database\Eloquent\Builder|Region translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Region translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereEnglishName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region withTranslation()
 */
	class Region extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * App\Models\RegionTranslation
 *
 * @property int $id
 * @property int $region_id
 * @property string $name
 * @property string $locale
 * @property string|null $slug
 * @method static \Illuminate\Database\Eloquent\Builder|RegionTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegionTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegionTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|RegionTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegionTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegionTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegionTranslation whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegionTranslation whereSlug($value)
 */
	class RegionTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Search
 *
 * @property int $id
 * @property string $term
 * @property int $count
 * @property int $chain_id
 * @property int $branch_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch $branch
 * @property-read \App\Models\Chain $chain
 * @method static \Illuminate\Database\Eloquent\Builder|Search newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Search newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Search query()
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereChainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereUpdatedAt($value)
 */
	class Search extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Taggable
 *
 * @property int $id
 * @property string $taggable_type
 * @property int $taggable_id
 * @property int $taxonomy_id
 * @property int|null $order_column
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Taggable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Taggable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Taggable query()
 * @method static \Illuminate\Database\Eloquent\Builder|Taggable whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taggable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taggable whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taggable whereTaggableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taggable whereTaggableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taggable whereTaxonomyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taggable whereUpdatedAt($value)
 */
	class Taggable extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Taxonomy
 *
 * @property int $id
 * @property string $uuid
 * @property int $creator_id
 * @property int $editor_id
 * @property int|null $parent_id
 * @property int $type 1:Category, 2: Tag, 3..n: CUSTOM
 * @property string|null $icon
 * @property int $view_count
 * @property int $left
 * @property int $right
 * @property int|null $depth
 * @property string $step
 * @property int|null $order_column
 * @property int $status 0:incomplete, 1:draft, 2:published, 3:Inactive, 4..n:CUSTOM
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Taxonomy[] $children
 * @property-read int|null $children_count
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $crossSellsProducts
 * @property-read int|null $cross_sells_products_count
 * @property-read \App\Models\User $editor
 * @property-read mixed $cover
 * @property-read mixed $is_published
 * @property-read mixed $link
 * @property-read mixed $status_name
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read Taxonomy|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Post[] $posts
 * @property-read int|null $posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Post[] $tagPosts
 * @property-read int|null $tag_posts_count
 * @property-read \App\Models\TaxonomyTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TaxonomyTranslation[] $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $upSellsProducts
 * @property-read int|null $up_sells_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $usersOfCategory
 * @property-read int|null $users_of_category_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $usersOfSkill
 * @property-read int|null $users_of_skill_count
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy foodCategories()
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy groceryCategories()
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|Node limitDepth($limit)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Query\Builder|Taxonomy onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy parents()
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy postCategories()
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy published()
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy query()
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy tags()
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy whereLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy whereRight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy whereStep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy whereViewCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy withTranslation()
 * @method static \Illuminate\Database\Query\Builder|Taxonomy withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Node withoutNode($node)
 * @method static \Illuminate\Database\Eloquent\Builder|Node withoutRoot()
 * @method static \Illuminate\Database\Eloquent\Builder|Node withoutSelf()
 * @method static \Illuminate\Database\Query\Builder|Taxonomy withoutTrashed()
 */
	class Taxonomy extends \Eloquent implements \Spatie\MediaLibrary\HasMedia, \App\Contracts\ShouldHaveTypes, \Astrotomic\Translatable\Contracts\Translatable {}
}

namespace App\Models{
/**
 * App\Models\TaxonomyTranslation
 *
 * @property int $id
 * @property int $taxonomy_id
 * @property string $locale
 * @property string $title
 * @property string|null $description
 * @property bool $is_auto_inserted
 * @method static \Illuminate\Database\Eloquent\Builder|TaxonomyTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaxonomyTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaxonomyTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|TaxonomyTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxonomyTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxonomyTranslation whereIsAutoInserted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxonomyTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxonomyTranslation whereTaxonomyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxonomyTranslation whereTitle($value)
 */
	class TaxonomyTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Timezone
 *
 * @property int $id
 * @property string $name
 * @property int $utc_offset
 * @property int $dst_offset
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $cities
 * @property-read int|null $cities_count
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone query()
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone whereDstOffset($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone whereUtcOffset($value)
 */
	class Timezone extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Translation
 *
 * @property int $id
 * @property int $status
 * @property string $group
 * @property string $key
 * @property int|null $order_column
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $is_published
 * @property-read mixed $status_name
 * @property-read \App\Models\TranslationTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TranslationTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Translation draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation group($groupName)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Translation published()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation withTranslation()
 */
	class Translation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TranslationTranslation
 *
 * @property int $id
 * @property int $translation_id
 * @property string $locale
 * @property string|null $value
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationTranslation whereTranslationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationTranslation whereValue($value)
 */
	class TranslationTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $first
 * @property string|null $last
 * @property string $username
 * @property string $email
 * @property string|null $password
 * @property string|null $phone_country_code
 * @property string|null $phone_number
 * @property string|null $bio
 * @property \Illuminate\Support\Carbon|null $dob
 * @property int|null $gender
 * @property string $wallet_reserved_total
 * @property string $wallet_free_total
 * @property int|null $profession_id
 * @property int|null $language_id Native language ID
 * @property int|null $currency_id
 * @property int|null $country_id
 * @property int|null $region_id
 * @property int|null $city_id
 * @property int|null $selected_address_id
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string $avg_rating
 * @property int $rating_count
 * @property int $view_count
 * @property int|null $order_column
 * @property object $mobile_app
 * @property mixed|null $social_networks
 * @property object $settings to handle all sort of settings including notification related such as is_notifiable by email or by push notifications ...etc
 * @property int $status 0:incomplete, 1:draft, 2:published, 3:Inactive, 4..n:CUSTOM
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property \Illuminate\Support\Carbon|null $phone_verified_at
 * @property \Illuminate\Support\Carbon|null $suspended_at
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $last_logged_in_at
 * @property \Illuminate\Support\Carbon|null $last_logged_out_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Location[] $addresses
 * @property-read int|null $addresses_count
 * @property-read \App\Models\City|null $city
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \App\Models\Country|null $country
 * @property-read \App\Models\Currency|null $currency
 * @property-read mixed $analyst
 * @property-read bool $avatar
 * @property-read bool $cover
 * @property-read mixed $international_phone
 * @property-read mixed $is_admin
 * @property-read bool $is_manager
 * @property-read mixed $is_owner
 * @property-read mixed $is_published
 * @property-read bool $is_super
 * @property-read mixed $is_user
 * @property-read mixed $name
 * @property-read mixed $status_name
 * @property-read mixed $translator
 * @property-read \App\Models\Language|null $language
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $order
 * @property-read int|null $order_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \App\Models\Region|null $region
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|User active()
 * @method static \Illuminate\Database\Eloquent\Builder|User draft()
 * @method static \Illuminate\Database\Eloquent\Builder|User inActive()
 * @method static \Illuminate\Database\Eloquent\Builder|User incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|User managers()
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|User notSuper()
 * @method static \Illuminate\Database\Eloquent\Builder|User owners()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User published()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvgRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirst($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLast($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoggedInAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoggedOutAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMobileApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoneCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoneVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSelectedAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSocialNetworks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSuspendedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereViewCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereWalletFreeTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereWalletReservedTotal($value)
 */
	class User extends \Eloquent implements \Spatie\MediaLibrary\HasMedia, \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App\Models{
/**
 * App\Models\WorkingHour
 *
 * @property int $id
 * @property string $workable_type
 * @property int $workable_id
 * @property int $day
 * @property string|null $opens_at
 * @property string|null $closes_at
 * @property bool $is_day_off
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $workable
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHour newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHour newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHour query()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHour whereClosesAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHour whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHour whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHour whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHour whereIsDayOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHour whereOpensAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHour whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHour whereWorkableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHour whereWorkableType($value)
 */
	class WorkingHour extends \Eloquent {}
}

