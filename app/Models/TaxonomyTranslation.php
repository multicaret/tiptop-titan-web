<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxonomyTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description'];
    protected $casts = ['is_auto_inserted' => 'boolean'];


}
