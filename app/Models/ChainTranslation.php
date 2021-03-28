<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ChainTranslation
 *
 * @property int $id
 * @property int $chain_id
 * @property string $title
 * @property string|null $description
 * @property string $locale
 * @method static \Illuminate\Database\Eloquent\Builder|ChainTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChainTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChainTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChainTranslation whereChainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChainTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChainTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChainTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChainTranslation whereTitle($value)
 * @mixin \Eloquent
 */
class ChainTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description'];
}
