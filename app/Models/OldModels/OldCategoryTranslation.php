<?php

namespace App\Models\OldModels;


use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;


class OldCategory extends Model
{
    use Translatable;


    protected $connection = 'mysql-old';
    protected $table = 'cms_categories_translations';
    protected $primaryKey = 'id';

    public static function attributesComparing(): array
    {
        return [
            'id' => 'id',
            'price' => 'price',
            'discount' => 'price_discount_amount',
            'discount_type' => 'price_discount_by_percentage',
            'discount_deadline' => 'price_discount_finished_at',
            'rating' => 'avg_rating',
            'rating_count' => 'rating_count',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
        ];
    }


}
