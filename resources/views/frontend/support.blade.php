@extends('layouts.frontend')
@section('title',__('Home'))
@section('content')
    @foreach(range(1,99) as $foo)
        <h4>{{$foo}}</h4>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi eum iure minima porro quis quo recusandae
           sed sunt temporibus vitae! Accusantium, aperiam at dicta dignissimos ipsam labore laboriosam nam sint!</p>
    @endforeach
    <style>
        #siqbtndiv {
            display: none !important;
        }
    </style>

    <script type="text/javascript">
        var $zoho = $zoho || {};
        $zoho.salesiq = $zoho.salesiq || {
            widgetcode: "94c11f2d4da8a1c9e93a5b5cfb54fb6fd62e7b0d07d28b4f840bd9ce3345d73a",
            values: {},
            ready: function () {
            }
        };
        var d = document;
        s = d.createElement("script");
        s.type = "text/javascript";
        s.id = "zsiqscript";
        s.defer = true;
        s.src = "https://salesiq.zoho.com/widget";
        t = d.getElementsByTagName("script")[0];
        t.parentNode.insertBefore(s, t);
        d.write("<div id='zsiqwidget'></div>");
    </script>

    <script>


        $zoho.salesiq.ready = function (e) {
            $zoho.salesiq.visitor.getGeoDetails();

            $zoho.salesiq.language("{{localization()->getCurrentLocale()}}");


            $zoho.salesiq.floatwindow.minimize(function (e) {
                $zoho.salesiq.chat.start();
                return false;
            });

            // alert("Ready!");
            setTimeout(function () {
                $zoho.salesiq.chat.start();


                // $("#siqiframe").hide()
                // console.log("children");
                // $("#siqiframe div.win_close").css('overflow', 'none');
                // console.log();
            }, 2000);
        }

        $zoho.salesiq.afterReady = function (visitorgeoinfo) {

            console.log("visitorgeoinfo", visitorgeoinfo);

            $zoho.salesiq.floatbutton.visible("hide");

            // if(visitorgeoinfo.Country == "UNITED STATES")   {
            //     $zoho.salesiq.floatbutton.visible("hide");
            // }
        }
    </script>

    {{--<script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>--}}

@endsection
