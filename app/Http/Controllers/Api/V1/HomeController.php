<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;

class HomeController extends BaseApiController
{
    public function root()
    {
        return $this->respondWithMessage('Welcome to '.config('app.name'));
    }

    public function index()
    {
        return $this->respond([]);
    }
}
