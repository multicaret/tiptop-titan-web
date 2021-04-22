<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\CartProductOptionSelection
 *
 * @property-read CartProduct $cartProduct
 * @property-read ProductOption $productOption
 * @property-read Model|Eloquent $selectable
 * @method static Builder|CartProductOptionSelection newModelQuery()
 * @method static Builder|CartProductOptionSelection newQuery()
 * @method static Builder|CartProductOptionSelection query()
 * @mixin Eloquent
 * @property int $id
 * @property int $cart_product_id
 * @property int $product_option_id
 * @property string $selectable_type
 * @property int $selectable_id
 * @property mixed|null $selectable_object
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|CartProductOptionSelection whereCartProductId($value)
 * @method static Builder|CartProductOptionSelection whereCreatedAt($value)
 * @method static Builder|CartProductOptionSelection whereId($value)
 * @method static Builder|CartProductOptionSelection whereProductOptionId($value)
 * @method static Builder|CartProductOptionSelection whereSelectableId($value)
 * @method static Builder|CartProductOptionSelection whereSelectableObject($value)
 * @method static Builder|CartProductOptionSelection whereSelectableType($value)
 * @method static Builder|CartProductOptionSelection whereUpdatedAt($value)
 */
class CartProductOptionSelection extends Pivot
{

    protected $table = 'cart_product_option_selection';

    protected $casts = [
        'product_option_object' => 'json',
    ];

    public static function boot()
    {
        static::creating(function ($model) {
            $model->selectable_object = $model->selectable_type::find($model->selectable_id);
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

    public function selectable(): MorphTo
    {
        return $this->morphTo();
    }
}
