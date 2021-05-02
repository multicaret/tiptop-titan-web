<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BranchTranslation
 *
 * @property int $id
 * @property int $branch_id
 * @property string $title
 * @property string|null $description
 * @property string $locale
 * @method static Builder|BranchTranslation newModelQuery()
 * @method static Builder|BranchTranslation newQuery()
 * @method static Builder|BranchTranslation query()
 * @method static Builder|BranchTranslation whereBranchId($value)
 * @method static Builder|BranchTranslation whereDescription($value)
 * @method static Builder|BranchTranslation whereId($value)
 * @method static Builder|BranchTranslation whereLocale($value)
 * @method static Builder|BranchTranslation whereTitle($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class BranchTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description'];
}
