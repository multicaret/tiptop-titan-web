<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Taggable
 *
 * @property int $id
 * @property string $taggable_type
 * @property int $taggable_id
 * @property int $taxonomy_id
 * @property int|null $order_column
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Taggable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Taggable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Taggable query()
 * @method static \Illuminate\Database\Eloquent\Builder|Taggable whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taggable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taggable whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taggable whereTaggableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taggable whereTaggableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taggable whereTaxonomyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taggable whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Taggable extends Model
{
//    protected $table = 'taggable';
}
