<?php

namespace App\Models\OldModels;


use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OldChain extends OldModel
{
    use Translatable;

    protected $table = 'jo3aan_restaurants';
    protected $with = ['translations'];
    protected $translationForeignKey = 'restaurant_id';
    protected array $translatedAttributes = ['title', 'description'];

    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_DISABLED = 'DISABLED';
    public const STATUS_SUSPENDED = 'SUSPENDED';


    public static function attributesComparing(): array
    {
        return [
            'id' => 'id',
            'delivery_app_percentage' => 'tiptop_delivery_app_percentage',
            'app_percentage' => 'restaurant_app_percentage',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
        ];
    }
}
