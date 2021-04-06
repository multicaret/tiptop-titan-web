<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    use HasFactory;
    use Translatable;

//    protected $fillable = [''];

    protected $translatedAttributes = ['group_title', 'option_title'];


    protected $casts = [
        'is_behaviour_method_excluding' => 'boolean',
        'extra_price' => 'double',
    ];

    public const TYPE_INCLUDING = 1;
    public const TYPE_EXCLUDING = 2;


    public const SELECTION_TYPE_SINGLE_VALUE = 1;
    public const SELECTION_TYPE_MULTIPLE_VALUE = 2;


}
