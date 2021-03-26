<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Search extends Model
{
    /**
     *
     * @param  string  $value
     * @return string
     */
    public function getTermAttribute($value)
    {
        return Str::title($value);
    }

    /**
     *
     * @param  string  $value
     * @return string
     */
    public function setTermAttribute($value)
    {
        $this->attributes['term'] = strtolower($value);
    }


    public function chain(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Chain::class, 'chain_id');
    }

    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
