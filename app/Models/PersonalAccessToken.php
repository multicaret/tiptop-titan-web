<?php


namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as PersonalAccessTokenAlias;

class PersonalAccessToken extends PersonalAccessTokenAlias
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'token',
        'abilities',
        'mobile_app_details',
    ];

}
