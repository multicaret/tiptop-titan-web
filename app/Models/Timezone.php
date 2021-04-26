<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Timezone
 *
 * @property int $id
 * @property string $name
 * @property int $utc_offset
 * @property int $dst_offset
 * @property-read Collection|City[] $cities
 * @property-read int|null $cities_count
 * @method static Builder|Timezone newModelQuery()
 * @method static Builder|Timezone newQuery()
 * @method static Builder|Timezone query()
 * @method static Builder|Timezone whereDstOffset($value)
 * @method static Builder|Timezone whereId($value)
 * @method static Builder|Timezone whereName($value)
 * @method static Builder|Timezone whereUtcOffset($value)
 * @mixin Eloquent
 */
class Timezone extends Model
{
    public $timestamps = false;

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
