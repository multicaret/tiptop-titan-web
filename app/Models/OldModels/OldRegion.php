<?php

namespace App\Models\OldModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\OldModels\OldRegion
 *
 * @property int $id
 * @property int|null $country_id
 * @property string $name_en
 * @property string $name_ar
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OldModels\OldCity[] $cities
 * @property-read int|null $cities_count
 * @method static \Illuminate\Database\Eloquent\Builder|OldRegion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OldRegion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OldRegion query()
 * @method static \Illuminate\Database\Eloquent\Builder|OldRegion whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldRegion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldRegion whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldRegion whereNameEn($value)
 * @mixin \Eloquent
 */
class OldRegion extends Model
{
    public $connection = 'mysql-old';
    protected $table = 'cities';
    protected $primaryKey = 'id';


    public function cities(): HasMany
    {
        return $this->hasMany(OldCity::class, 'city_id');
    }

}
