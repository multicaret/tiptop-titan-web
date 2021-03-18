<?php

namespace App\Http\Controllers;


use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.index');
    }

    public function staticMap()
    {
        $apiKey = env('GOOGLE_MAPS_API');
        $zoom = 14;
        $dimensions = '414x212';

        $marker1LocationLatitude = config('defaults.geolocation.latitude') + 0.001;
        $marker1LocationLongitude = config('defaults.geolocation.longitude') + 0.001;
        $marker1 = urlencode("color:blue|label:Me|{$marker1LocationLatitude},{$marker1LocationLongitude}");
        $marker2LocationLatitude = config('defaults.geolocation.latitude') + 0.002;
        $marker2LocationLongitude = config('defaults.geolocation.longitude') + 0.002;
        $marker2IconUrl = "http://titan.trytiptop.app/favicon.png?2";
        $marker2 = urlencode("icon:{$marker2IconUrl}|{$marker2LocationLatitude},{$marker2LocationLongitude}");
//        $marker2 = urlencode("color:green|label:G|{$marker2LocationLatitude},{$marker2LocationLongitude}");

        $locationLatitude = ($marker1LocationLatitude + $marker2LocationLatitude) / 2;
        $locationLongitude = ($marker1LocationLongitude + $marker2LocationLongitude) / 2;
        $url = "https://maps.googleapis.com/maps/api/staticmap?center={$locationLatitude},{$locationLongitude}&zoom=$zoom&size=$dimensions&maptype=roadmap
&markers={$marker1}&markers={$marker2}&key=$apiKey";

        return '<img src="'.$url.'"/> <a href="'.$url.'">Link</a>';
    }

    public function foo()
    {
        $super = User::first();


    }
}
