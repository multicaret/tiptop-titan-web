<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Cart
 *
 * @property int $id
 * @property int $user_id
 * @property int $chain_id
 * @property int $branch_id
 * @property float $total
 * @property float $without_discount_total
 * @property int|null $crm_id
 * @property int|null $crm_user_id
 * @property int $status 0:In Progress, 1: Completed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Branch $branch
 * @property-read Collection|CartProduct[] $cartProducts
 * @property-read int|null $cart_products_count
 * @property-read Chain $chain
 * @property-read Collection|Product[] $products
 * @property-read int|null $products_count
 * @property-read User $user
 * @method static Builder|Cart newModelQuery()
 * @method static Builder|Cart newQuery()
 * @method static Builder|Cart query()
 * @method static Builder|Cart whereBranchId($value)
 * @method static Builder|Cart whereChainId($value)
 * @method static Builder|Cart whereCreatedAt($value)
 * @method static Builder|Cart whereCrmId($value)
 * @method static Builder|Cart whereCrmUserId($value)
 * @method static Builder|Cart whereId($value)
 * @method static Builder|Cart whereStatus($value)
 * @method static Builder|Cart whereTotal($value)
 * @method static Builder|Cart whereUpdatedAt($value)
 * @method static Builder|Cart whereUserId($value)
 * @method static Builder|Cart whereWithoutDiscountTotal($value)
 * @mixin Eloquent
 */
class Cart extends Model
{

    public const STATUS_IN_PROGRESS = 0;
    public const STATUS_COMPLETED = 1;

    protected $casts = [
        'total' => 'double',
        'without_discount_total' => 'double',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chain(): BelongsTo
    {
        return $this->belongsTo(Chain::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function cartProducts()
    {
        return $this->hasMany(CartProduct::class, 'cart_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'cart_product', 'cart_id', 'product_id')
                    ->withPivot('quantity')
                    ->withPivot('product_object')
                    ->withTimestamps();
    }

    public static function retrieve($chainId, $branchId, $userId = null, $status = self::STATUS_IN_PROGRESS): Cart
    {
        if (is_null($userId)) {
            $userId = auth()->id();
        }

        if (is_null($cart = Cart::where('user_id', $userId)
                                ->where('branch_id', $branchId)
                                ->where('status', $status)
                                ->first())) {
            $cart = new Cart();
            $cart->chain_id = $chainId;
            $cart->branch_id = $branchId;
            $cart->user_id = $userId;
            $cart->status = $status;
            $cart->save();
        }

        return $cart;
    }
}
