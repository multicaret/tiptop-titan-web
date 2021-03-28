<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BranchTranslation
 *
 * @property int $id
 * @property int $branch_id
 * @property string $title
 * @property string|null $description
 * @property string $locale
 * @method static \Illuminate\Database\Eloquent\Builder|BranchTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchTranslation whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchTranslation whereTitle($value)
 * @mixin \Eloquent
 */
class BranchTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description'];
}
