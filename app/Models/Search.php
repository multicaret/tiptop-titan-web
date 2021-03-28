<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\Search
 *
 * @property int $id
 * @property string $locale
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
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Search extends Model
{
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


    public function chain(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Chain::class, 'chain_id');
    }

    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
