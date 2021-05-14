@extends('layouts.error')

@section('content')
    <section class="page_404">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 text-center ">
                    <img src="/images/404.jpg" height="400px" style="border-radius: 40px;">

                    <div class="content_box_404 mt-5">
                        <h3 class="h2">
                            Looks Like You're Lost
                        </h3>

                        <p>The page you are looking for is not available!</p>

                        <a href="{{ url('/') }}" class="link_404">Go Home</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
