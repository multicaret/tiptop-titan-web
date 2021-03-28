<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
 * @mixin \Eloquent
 */
class Timezone extends Model
{
    public $timestamps = false;

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
