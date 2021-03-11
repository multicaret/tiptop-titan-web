@extends('layouts.error')

@section('content')
    <section class="page_404">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 text-center ">
                    <div class="four_zero_four_bg">
                        <h1 class="text-center ">404</h1>
                    </div>

                    <div class="content_box_404">
                        <h3 class="h2">
                            Looks Like You're Lost
                        </h3>

                        <p>The page you are looking for is not available!</p>

                        <a href="{{ route('home') }}" class="link_404">Go Home</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('head')
    <style>

        /*======================
            404 page
        =======================*/


        .page_404 {
            padding: 40px 0;
            background: #fff;
            width: 100%;
        }

        .page_404 img {
            width: 100%;
        }

        .four_zero_four_bg {
            background-image: url('/images/404.gif');
            height: 400px;
            background-position: center;
            background-repeat: no-repeat;
        }


        .four_zero_four_bg h1 {
            font-size: 80px;
        }

        .four_zero_four_bg h3 {
            font-size: 80px;
        }

        .link_404 {
            color: #39ac31 !important;
            padding: 10px 20px;
            border: 1px solid #39ac31;
            margin: 20px 0;
            display: inline-block;
            border-radius: 5px;

        }

        .link_404:hover {
            text-decoration: none;
            background: #56c34f;
            color: #fff !important;
        }

        .content_box_404 {
            margin-top: -50px;
        }
    </style>
@endpush
