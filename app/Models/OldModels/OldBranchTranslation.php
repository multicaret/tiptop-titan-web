<?php

namespace App\Models\OldModels;


use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;



/**
 * App\Models\OldModels\OldBranchTranslation
 *
 * @property int $id
 * @property int $branch_id
 * @property string $locale
 * @property string $title_suffex
 * @property string|null $description
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranchTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranchTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranchTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranchTranslation whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranchTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranchTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranchTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldBranchTranslation whereTitleSuffex($value)
 * @mixin \Eloquent
 */
class OldBranchTranslation extends Model
{
    protected $connection = 'mysql-old';
    protected $table = 'jo3aan_branches_translations';
    protected $primaryKey = 'id';


    public static function attributesComparing(): array
    {
        return [
            'title_suffex' => 'title',
            'description' => 'description',
            'locale' => 'locale',
            'branch_id' => 'branch_id',
        ];
    }
}
