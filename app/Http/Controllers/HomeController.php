<?php

namespace App\Http\Controllers;


use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.index');
    }

    public function foo()
    {
        $super = User::first();


    }
}
