<?php

namespace App\Models;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\SearchTaggable
 *
 * @property int $id
 * @property string $search_taggable_type
 * @property int $search_taggable_id
 * @property int $taxonomy_id
 * @property int|null $order_column
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|\Eloquent $searchTaggable
 * @method static Builder|SearchTaggable newModelQuery()
 * @method static Builder|SearchTaggable newQuery()
 * @method static Builder|SearchTaggable query()
 * @method static Builder|SearchTaggable whereCreatedAt($value)
 * @method static Builder|SearchTaggable whereId($value)
 * @method static Builder|SearchTaggable whereOrderColumn($value)
 * @method static Builder|SearchTaggable whereSearchTaggableId($value)
 * @method static Builder|SearchTaggable whereSearchTaggableType($value)
 * @method static Builder|SearchTaggable whereTaxonomyId($value)
 * @method static Builder|SearchTaggable whereUpdatedAt($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class SearchTaggable extends Model
{
    protected $table = 'search_taggables';

    public function searchTaggable()
    {
        return $this->morphTo();
    }
}
