<?php

namespace App\Models;

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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostTranslation whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostTranslation whereExcerpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostTranslation whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostTranslation wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostTranslation whereTitle($value)
 * @mixin \Eloquent
 */
class PostTranslation extends Model
{

    public $timestamps = false;
    protected $fillable = ['title', 'content', 'excerpt', 'notes'];
}
