<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\CartProductOption
 *
 * @property int $id
 * @property int $cart_product_id
 * @property int|null $product_option_id
 * @property array|null $product_option_object
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\CartProduct $cartProduct
 * @property-read \App\Models\ProductOption|null $productOption
 * @property-read Collection|\App\Models\CartProductOptionSelection[] $selections
 * @property-read int|null $selections_count
 * @method static Builder|CartProductOption newModelQuery()
 * @method static Builder|CartProductOption newQuery()
 * @method static Builder|CartProductOption query()
 * @method static Builder|CartProductOption whereCartProductId($value)
 * @method static Builder|CartProductOption whereCreatedAt($value)
 * @method static Builder|CartProductOption whereId($value)
 * @method static Builder|CartProductOption whereProductOptionId($value)
 * @method static Builder|CartProductOption whereProductOptionObject($value)
 * @method static Builder|CartProductOption whereUpdatedAt($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class CartProductOption extends Model
{
    protected $casts = [
        'product_option_object' => 'json',
    ];

    protected $fillable = [
        'cart_product_id',
        'product_option_id',
    ];

    public static function boot()
    {
        static::creating(function ($model) {
            $model->product_option_object = ProductOption::find($model->product_option_id);
        });
        parent::boot();
    }

    public function cartProduct(): BelongsTo
    {
        return $this->belongsTo(CartProduct::class, 'cart_product_id');
    }

    public function productOption(): BelongsTo
    {
        return $this->belongsTo(ProductOption::class, 'product_option_id');
    }


    public function selections()
    {
        return $this->hasMany(CartProductOptionSelection::class, 'cart_product_option_id');
    }
}
