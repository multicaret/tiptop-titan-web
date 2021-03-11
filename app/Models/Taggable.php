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
 * @property int|null $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taggable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taggable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taggable query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taggable whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taggable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taggable whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taggable whereTaggableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taggable whereTaggableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taggable whereTaxonomyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taggable whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $order_column
 * @method static \Illuminate\Database\Eloquent\Builder|Taggable whereOrderColumn($value)
 */
class Taggable extends Model
{
//    protected $table = 'taggable';
}
