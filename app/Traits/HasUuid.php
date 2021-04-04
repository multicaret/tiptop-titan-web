<?php

namespace App\Traits;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait HasUuid
{

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->uuid = Controller::uuid().mt_rand(10, 99);
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }


    /**
     * @param  Model  $model
     * @param  Request  $request
     * @param  string  $idSource
     * @param  string  $slugSource
     *
     * @return bool
     */
    public static function adjustShowURLSlug(
        Model $model,
        Request $request,
        string $slugSource = 'title',
        string $idSource = 'uuid'
    ): bool {
        $urlExploded = explode('/', urldecode($request->getRequestUri()));
        $slugAndUuid = $urlExploded[count($urlExploded) - 1];

        //Method 1
        $slug = strstr($slugAndUuid, '-'.$model->{$idSource}, 1);


        return $slug !== self::slugify($model->{$slugSource});

        //Method 2: alternative to Method 1
        /*$slugExploded = explode('-', $slugAndUuid);
//        $uuid = $slugExploded[count($slugExploded) - 1];
        unset($slugExploded[count($slugExploded) - 1]);
        $slug = implode('-', $slugExploded);
        if (substr_count($slug, '-') <= 1) {
            return redirect($competition->path);
        }*/
    }

    public static function slugify($text)
    {
        $text = trim(strtolower($text), '-');

        return trim(preg_replace('#(\p{P}|\p{C}|\p{S}|\p{Z})+#u', '-', $text), '-');
    }

}
