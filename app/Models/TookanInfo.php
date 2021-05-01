<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TookanInfo
 *
 * @property-read Model|\Eloquent $tookanable
 * @method static \Illuminate\Database\Eloquent\Builder|TookanInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TookanInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TookanInfo query()
 * @mixin \Eloquent
 */
class TookanInfo extends Model
{
    use HasFactory;

    protected $table = 'tookans';

    protected $guarded = [];

    public function tookanable()
    {
        return $this->morphTo();
    }
}
