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
 * @property int|null $chain_id
 * @property int|null $branch_id
 * @property float $total
 * @property float $without_discount_total
 * @property int|null $crm_id
 * @property int|null $crm_user_id
 * @property int $status 0:In Progress, 1: Completed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read Collection|\App\Models\CartProduct[] $cartProducts
 * @property-read int|null $cart_products_count
 * @property-read \App\Models\Chain|null $chain
 * @property-read Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \App\Models\User $user
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
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class Cart extends Model
{

    public const STATUS_IN_PROGRESS = 0;
    public const STATUS_COMPLETED = 1;

    protected $casts = [
        'total' => 'double',
        'without_discount_total' => 'double',
    ];

    /**
     * @param  int|null  $userId
     * @param $branchId
     * @param  int  $status
     * @return Cart|Model|object|null
     */
    public static function getCurrentlyActiveCart(
        ?int $userId,
        ?int $branchId = null,
        int $status = self::STATUS_IN_PROGRESS
    ) {
        $cartQuery = Cart::where('user_id', $userId)
                         ->where('status', $status);

        if ( ! is_null($branchId)) {
            $cartQuery = $cartQuery->where('branch_id', $branchId);
        } else {
            $cartQuery = $cartQuery->whereNull('branch_id');
        }

        return $cartQuery->first();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
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

    public static function retrieve(
        ?int $chainId = null,
        ?int $branchId = null,
        ?int $userId = null,
        $status = self::STATUS_IN_PROGRESS
    ): Cart {
        if (is_null($userId)) {
            $userId = auth()->id();
        }

        if (is_null($cart = self::getCurrentlyActiveCart($userId, $branchId, $status))) {
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
