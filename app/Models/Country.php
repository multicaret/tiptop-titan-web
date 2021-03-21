<?php

namespace App\Models;

use App\Traits\HasStatuses;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use Translatable,
        HasStatuses;

    const STATUS_INCOMPLETE = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_INACTIVE = 3;


    const IRAQ_COUNTRY_ID = 107;
    const TURKEY_COUNTRY_ID = 225;

    protected $translatedAttributes = ['name', 'slug'];
    protected $fillable = ['name', 'slug'];
    protected $with = ['translations'];
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    /*protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ActiveScope);
    }*/

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function timezone()
    {
        return $this->belongsTo(Timezone::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function regions()
    {
        return $this->hasMany(Region::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public static function getAll()
    {
        return cache()->tags('countries')
                      ->rememberForever('countries', function () {
                          return Country::all();
                      });
    }

}
