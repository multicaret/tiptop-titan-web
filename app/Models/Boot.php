<?php

namespace App\Models;

use App\Traits\HasTypes;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Boot extends Model
{
    use Translatable,
        HasTypes;

    //Application Types
    const TYPE_APPLICATION_CUSTOMER = 1;
    const TYPE_APPLICATION_RESTAURANT = 2;
    const TYPE_APPLICATION_DRIVER = 3;

    //Platform Types
    const TYPE_PLATFORM_IOS = 1;
    const TYPE_PLATFORM_ANDROID = 2;

    const FORCE_UPDATE_METHOD_DISABLED = 0;
    const FORCE_UPDATE_METHOD_SOFT = 1;
    const FORCE_UPDATE_METHOD_HARD = 2;


    protected $translatedAttributes = ['title', 'data_translated'];
    protected $fillable = ['data', 'title', 'data_translated'];
    protected $casts = ['data' => 'json'];

}

