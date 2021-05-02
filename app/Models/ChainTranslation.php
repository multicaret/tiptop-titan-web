<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ChainTranslation
 *
 * @property int $id
 * @property int $chain_id
 * @property string $title
 * @property string|null $description
 * @property string $locale
 * @method static Builder|ChainTranslation newModelQuery()
 * @method static Builder|ChainTranslation newQuery()
 * @method static Builder|ChainTranslation query()
 * @method static Builder|ChainTranslation whereChainId($value)
 * @method static Builder|ChainTranslation whereDescription($value)
 * @method static Builder|ChainTranslation whereId($value)
 * @method static Builder|ChainTranslation whereLocale($value)
 * @method static Builder|ChainTranslation whereTitle($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class ChainTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description'];
}
