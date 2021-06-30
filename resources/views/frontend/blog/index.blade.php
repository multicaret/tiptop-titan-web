@extends('layouts.frontend')
@section('title',__('strings.blog'))
@section('content')
    <div class="container space-top-2 space-bottom-2">

    <!-- Title -->
    <div class="row mb-5">
        <div class="col-6">
        </div>
        <div class="col-6 text-right">
        </div>
    </div>
    <!-- End Title -->

    <div class="row mb-3">
        @foreach($posts as $post)
           <div class="col-sm-6 col-lg-4 mb-3 mb-sm-8">
            <!-- Blog Card -->
            <article class="card h-100">
                <div class="card-img-top position-relative">
                    <img class="card-img-top" src="{{$post->cover}}" alt="Image Description">
                    <figure class="ie-curved-y position-absolute right-0 bottom-0 left-0 mb-n1">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 1920 100.1">
                            <path fill="#fff" d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z"/>
                        </svg>
                    </figure>
                </div>

                <div class="card-body">
                    <h3><a class="text-inherit" href="{{route('blog.show', $post )}}">{{$post->title}}</a></h3>
                </div>

                <div class="card-footer border-0 pt-0">
                    <div class="media align-items-center">

                        <div class="media-body d-flex justify-content-end text-muted font-size-1 ml-2">
                              {{$post->created_at->format('M d')}}
                        </div>
                    </div>
                </div>
            </article>
            <!-- End Blog Card -->
        </div>
        @endforeach
    {{--    <div class="col-sm-6 col-lg-4 mb-3 mb-sm-8">
            <!-- Blog Card -->
            <article class="card bg-dark text-white h-100">
                <div class="card-body p-4 p-lg-5">
                    <span class="badge badge-primary py-2 px-3 mb-5">Featured</span>
                    <h3 class="h2"><a class="text-white" href="blog-single-article.html">Announcing Front Tutorials: Master Adobe Ai - Part II</a></h3>
                    <p>A new tutorial to make it easier to master Adobe Ai.</p>
                </div>
                <div class="card-footer bg-dark border-0 pt-0 px-5 pb-5">
                    <div class="media align-items-center">
                        <div class="avatar-group">
                            <a class="avatar avatar-xs avatar-circle" href="#" data-toggle="tooltip" data-placement="top" title="Aaron Larsson">
                                <img class="avatar-img" src="./assets/img/100x100/img3.jpg" alt="Image Description">
                            </a>
                        </div>
                        <div class="media-body d-flex justify-content-end text-white-70 font-size-1 ml-2">
                            July 15
                        </div>
                    </div>
                </div>
            </article>
            <!-- End Blog Card -->
        </div>--}}

    </div>



    <!-- Pagination -->
    <nav aria-label="Page navigation">
        {{$posts->links()}}
    </nav>
    <!-- End Pagination -->

    </div>
@endsection
