<?php

namespace App\Models;

use Baum\Node as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Comment
 *
 * @property int $id
 * @property int|null $parent_id
 * @property int $user_id
 * @property int $type 1:Comment, 2: Review, 3..n: CUSTOM
 * @property string|null $content
 * @property string $commentable_type
 * @property int $commentable_id
 * @property int $left
 * @property int $right
 * @property int|null $depth
 * @property int $votes
 * @property int $status 1:shown, 2:reported
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Comment[] $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $commentable
 * @property-read Comment|null $parent
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Node limitDepth($limit)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Query\Builder|Comment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereRight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereVotes($value)
 * @method static \Illuminate\Database\Query\Builder|Comment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Node withoutNode($node)
 * @method static \Illuminate\Database\Eloquent\Builder|Node withoutRoot()
 * @method static \Illuminate\Database\Eloquent\Builder|Node withoutSelf()
 * @method static \Illuminate\Database\Query\Builder|Comment withoutTrashed()
 * @mixin \Eloquent
 */
class Comment extends Model
{
    use SoftDeletes;

    const STATUS_SHOWN = 1;
    const STATUS_REPORTED = 2;

    /**
     * Determine if the comment has children.
     *
     * @return bool
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    /**
     * Get all of comment's children.
     *
     * @param  array  $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getChildren($columns = ['*'])
    {
        return $this->children()->get($columns);
    }

    /**
     * Get all of the owning commentable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * Comment belongs to a user.
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
