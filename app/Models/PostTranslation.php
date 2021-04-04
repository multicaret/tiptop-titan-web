<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PostTranslation
 *
 * @property int $id
 * @property int $post_id
 * @property string $locale
 * @property string $title
 * @property string|null $content
 * @property string|null $excerpt
 * @property string|null $notes
 * @method static Builder|PostTranslation newModelQuery()
 * @method static Builder|PostTranslation newQuery()
 * @method static Builder|PostTranslation query()
 * @method static Builder|PostTranslation whereContent($value)
 * @method static Builder|PostTranslation whereExcerpt($value)
 * @method static Builder|PostTranslation whereId($value)
 * @method static Builder|PostTranslation whereLocale($value)
 * @method static Builder|PostTranslation whereNotes($value)
 * @method static Builder|PostTranslation wherePostId($value)
 * @method static Builder|PostTranslation whereTitle($value)
 * @mixin Eloquent
 */
class PostTranslation extends Model
{

    public $timestamps = false;
    protected $fillable = ['title', 'content', 'excerpt', 'notes'];
}
