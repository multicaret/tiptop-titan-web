<?php

namespace App\Models;

use App\Traits\HasAppTypes;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * App\Models\Search
 *
 * @property int $id
 * @property string $locale
 * @property string $term
 * @property int $count
 * @property int|null $chain_id
 * @property int|null $branch_id
 * @property int $type 1:Market, 2: Food
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Chain|null $chain
 * @property-read bool $is_food
 * @property-read bool $is_grocery
 * @method static Builder|Search foods()
 * @method static Builder|Search groceries()
 * @method static Builder|Search newModelQuery()
 * @method static Builder|Search newQuery()
 * @method static Builder|Search query()
 * @method static Builder|Search whereBranchId($value)
 * @method static Builder|Search whereChainId($value)
 * @method static Builder|Search whereCount($value)
 * @method static Builder|Search whereCreatedAt($value)
 * @method static Builder|Search whereId($value)
 * @method static Builder|Search whereLocale($value)
 * @method static Builder|Search whereTerm($value)
 * @method static Builder|Search whereType($value)
 * @method static Builder|Search whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Search extends Model
{
    use HasAppTypes;

    public const CHANNEL_GROCERY_OBJECT = 1;
    public const CHANNEL_FOOD_OBJECT = 2;

    /**
     *
     * @param  string  $value
     * @return string
     */
    public function getTermAttribute($value)
    {
        return Str::title($value);
    }

    /**
     *
     * @param  string  $value
     * @return string
     */
    public function setTermAttribute($value)
    {
        $this->attributes['term'] = strtolower($value);
    }


    public function chain(): BelongsTo
    {
        return $this->belongsTo(Chain::class, 'chain_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
