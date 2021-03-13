<?php

namespace App\Models;

use App\Traits\HasStatuses;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use Translatable,
        HasStatuses;

    const STATUS_INCOMPLETE = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_INACTIVE = 3;

    protected $translatedAttributes = ['name'];
    protected $fillable = ['name'];
    protected $with = ['translations'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
//        static::addGlobalScope(new ActiveScope);
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_rtl' => 'boolean',
    ];

    public function countries()
    {
        return $this->hasMany(Country::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot(['level'])
                    ->withTimestamps();
    }
}
