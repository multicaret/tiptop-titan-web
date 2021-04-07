<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductOptionTranslation
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionTranslation query()
 * @mixin \Eloquent
 */
class ProductOptionTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['group_title', 'option_title'];
}
