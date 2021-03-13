<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Search extends Model
{

    public function chain(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Chain::class, 'chain_id');
    }

    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function setTermAttribute($value)
    {
        $this->attributes['term'] = ucfirst($value);
    }
}
