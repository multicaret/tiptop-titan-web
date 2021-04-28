<?php

namespace App\Models\OldModels;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OldModels\OldCity
 *
 * @property int $id
 * @property int|null $city_id
 * @property string $name_ar
 * @property string $name_en
 * @property int $municipality_key
 * @property int $municipality_city
 * @method static \Illuminate\Database\Eloquent\Builder|OldCity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OldCity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OldCity query()
 * @method static \Illuminate\Database\Eloquent\Builder|OldCity whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldCity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldCity whereMunicipalityCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldCity whereMunicipalityKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldCity whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldCity whereNameEn($value)
 * @mixin \Eloquent
 */
class OldCity extends Model
{
    public $connection = 'mysql-old';
    protected $table = 'municipalities';
    protected $primaryKey = 'id';

}
