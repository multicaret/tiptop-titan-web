<?php

namespace App\Models;

use App\Traits\HasStatuses;
use App\Traits\HasTypes;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Boot extends Model
{
    use Translatable,
        HasTypes;

    //Application Types
    const TYPE_APPLICATION_CUSTOMER = 0;
    const TYPE_APPLICATION_RESTAURANT = 1;
    const TYPE_APPLICATION_DRIVER = 2;
    //Platform Types
    const TYPE_PLATFORM_IOS = 0;
    const TYPE_PLATFORM_ANDROID = 1;

    protected $translatedAttributes = ['title', 'content', 'excerpt', 'notes'];
    protected $fillable = ['data', 'title'];
    protected $with = ['translations'];
    protected $casts = ['data' => 'json'];

    protected $attributes = [
        'data' => '{
            "versionCode": "0",
            "versionNumber": "0",
            "device": {
                "manufacturer": "",
                "model": "",
                "platform": "",
                "serial": "",
                "uuid": "",
                "version": ""
            }
        }',
    ];
}

