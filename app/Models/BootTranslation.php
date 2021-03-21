<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BootTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['title', 'data_translated'];

    protected $casts = [
        'data_translated' => 'json',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (BootTranslation $bootTranslation) {
            $dataTranslated = [
                'alert' => [
                    [
                        'title' => '',
                        'message' => '',
                    ],
                ],
            ];
            $bootTranslation->data_translated = $dataTranslated;
        });
    }

}
