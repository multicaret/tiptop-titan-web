<?php

namespace App\Models\OldModels;


use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;


class OldChainTranslation extends Model
{
    protected $connection = 'mysql-old';
    protected $table = 'jo3aan_restaurants_translations';


    public static function attributesComparing(): array
    {
        return [
            'title' => 'title',
            'description' => 'description',
            'locale' => 'locale',
            'restaurant_id' => 'chain_id',
        ];
    }
}
