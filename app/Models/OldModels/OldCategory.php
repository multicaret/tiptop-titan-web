<?php

namespace App\Models\OldModels;


use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


class OldCategory extends Model
{
    use Translatable;


    protected $connection = 'mysql-old';
    protected $table = 'cms_categories';
    protected $primaryKey = 'id';
    protected $with = ['translations'];
    protected array $translatedAttributes = ['title', 'description', 'icon', 'image'];
    protected $translationForeignKey = 'category_id';

    public const MEALS = 'MEALS';
    public const KITCHENS = 'KITCHENS';
    public const RESTAURANTS = 'RESTAURANTS';
    public const DISHES = 'DISHES';
    public const SIDE_DISHES = 'SIDE_DISHES';



    protected static function booted()
    {
        static::addGlobalScope('ancient', function (Builder $builder) {
            $beginsAt = Carbon::parse('2020-12-25')->setTimeFromTimeString('00:00');
            $builder->where('created_at', '>=', $beginsAt);
        });
    }


    public static function attributesComparing(): array
    {
        return [
            'id' => 'id',
            'parent_id' => 'parent_id',
            'sort_order' => 'order_column',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
        ];
    }


}
