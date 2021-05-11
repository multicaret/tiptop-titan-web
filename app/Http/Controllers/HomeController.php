<?php

namespace App\Http\Controllers;


use App\Http\Resources\ProductOptionResource;
use App\Models\ProductOption;
use App\Utilities\PermissionsGenerator;

class HomeController extends Controller
{
    public function download($platform)
    {
        if ($platform != 'ios' && $platform != 'android') {
            return redirect('/');
        }

        return view('frontend.download-app', compact('platform'));
    }

    public function index()
    {
        return view('frontend.home');
    }

    public function staticMap()
    {
        $apiKey = env('GOOGLE_MAPS_API');
//        $zoom = 14;
        $dimensions = '640x320';

        $marker1LocationLatitude = config('defaults.geolocation.latitude') + 0.021;
        $marker1LocationLongitude = config('defaults.geolocation.longitude') + 0.001;
        $marker1IconUrl = str_replace('titan.test', 'titan.trytiptop.app',
            url(config('defaults.images.tiptop_marker_icon_small')));
        $marker1 = urlencode("icon:{$marker1IconUrl}|{$marker1LocationLatitude},{$marker1LocationLongitude}");
        $marker2LocationLatitude = config('defaults.geolocation.latitude') + 0.002;
        $marker2LocationLongitude = config('defaults.geolocation.longitude') + 0.002;
        $marker2IconUrl = str_replace('titan.test', 'titan.trytiptop.app',
            url(config('defaults.images.address_home_marker_icon_small')));
        $marker2 = urlencode("icon:{$marker2IconUrl}|{$marker2LocationLatitude},{$marker2LocationLongitude}");
//        $marker2 = urlencode("color:green|label:G|{$marker2LocationLatitude},{$marker2LocationLongitude}");

        $locationLatitude = ($marker1LocationLatitude + $marker2LocationLatitude) / 2;
        $locationLongitude = ($marker1LocationLongitude + $marker2LocationLongitude) / 2;
        $url = "https://maps.googleapis.com/maps/api/staticmap?center={$locationLatitude},{$locationLongitude}&size=$dimensions&maptype=roadmap
&markers={$marker1}&markers={$marker2}&key=$apiKey";

//        return '<img src="'.$url.'" height="100%"/>';

        $image = file_get_contents($url);
        $fp = fopen('map.png', 'w+');

        fputs($fp, $image);
        fclose($fp);
        unset($image);

        return 'Done';
    }

    public function foo()
    {

        dd(PermissionsGenerator::getAllRolesPermissions('super'));

        $object = ProductOption::first();

        return new ProductOptionResource($object);

    }
}
