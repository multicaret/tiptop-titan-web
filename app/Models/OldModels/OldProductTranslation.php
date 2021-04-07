<?php

namespace App\Models\OldModels;



use Illuminate\Database\Eloquent\Model;

class OldProductTranslation extends Model
{
    protected $connection = 'mysql-old';
    protected $table = 'jo3aan_dishes_translations';
    protected $primaryKey = 'id';

    public static function attributesComparing(): array
    {
        return [
            'title' => 'title',
            'description' => 'description',
            'locale' => 'locale',
            'dish_id' => 'product_id',
        ];
    }


}
