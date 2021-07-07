@extends('layouts.frontend')
@section('title', $post->title)
@push('styles')
    <style>
        #article-content img {
            width: 100% !important;
        }
    </style>
@endpush
@section('content')


    <div class="w-lg-60 mt-3 mx-lg-auto">


        <!-- Author -->
        <div class="border-top border-bottom py-2 mb-5">
            <div class="row align-items-md-center">
                <div class="col-md-7 mb-5 mb-md-0">
                    <div class="media align-items-center">

                        <div class="media-body font-size-1 ml-3">
                            {{--
                                                        <span class="h6"><a href="blog-profile.html">Hanna Wolfe</a> <button type="button" class="btn btn-xs btn-soft-primary font-weight-bold transition-3d-hover py-1 px-2 ml-1">Follow</button></span>
                            --}}
                            <span class="d-block text-muted">{{$post->created_at->format('M d, Y')}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    {{--                    <div class="d-flex justify-content-md-end align-items-center">
                                            <span class="d-block small font-weight-bold text-cap mr-2">Share:</span>

                                            <a class="btn btn-xs btn-icon btn-soft-secondary rounded-circle ml-2" href="#">
                                                <i class="fab fa-facebook-f"></i>
                                            </a>
                                            <a class="btn btn-xs btn-icon btn-soft-secondary rounded-circle ml-2" href="#">
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                            <a class="btn btn-xs btn-icon btn-soft-secondary rounded-circle ml-2" href="#">
                                                <i class="fab fa-instagram"></i>
                                            </a>
                                            <a class="btn btn-xs btn-icon btn-soft-secondary rounded-circle ml-2" href="#">
                                                <i class="fab fa-telegram"></i>
                                            </a>
                                        </div>--}}
                </div>
            </div>
        </div>
        <!-- End Author -->
        <div id="article-content">
            {!! $post->content !!}
        </div>

    </div>


    <!-- Blog Card Section -->
    <div class="container">
        <div class="w-lg-75 border-top space-2 mx-lg-auto">
            @if(!empty($previous) || !empty($next))
                <div class="mb-3 mb-sm-5">
                    <h3>{{__('strings.related_posts')}}</h3>
                </div>
            @endif
            <div class="row">
                @if(!empty($previous))
                    <div class="col-md-4">
                        <article class="card h-100">
                            <div class="card-img-top position-relative">
                                <img class="card-img-top" src="{{$previous->cover}}" alt="Image Description">
                                <figure class="ie-curved-y position-absolute right-0 bottom-0 left-0 mb-n1">
                                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 1920 100.1">
                                        <path fill="#fff" d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z"/>
                                    </svg>
                                </figure>
                            </div>

                            <div class="card-body">
                                <h3><a class="text-inherit"
                                       href="{{route('blog.show', $previous )}}">{{$previous->title}}</a></h3>
                            </div>

                            <div class="card-footer border-0 pt-0">
                                <div class="media align-items-center">

                                    <div class="media-body d-flex justify-content-end text-muted font-size-1 ml-2">
                                        {{$previous->created_at->format('M d')}}
                                    </div>
                                </div>
                            </div>
                        </article>
                        <!-- End Blog Card -->
                    </div>
                @endif
                @if(!empty($next))
                    <div class="col-md-4">
                        <article class="card h-100">
                            <div class="card-img-top position-relative">
                                <img class="card-img-top" src="{{$next->cover}}" alt="Image Description">
                                <figure class="ie-curved-y position-absolute right-0 bottom-0 left-0 mb-n1">
                                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 1920 100.1">
                                        <path fill="#fff" d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z"/>
                                    </svg>
                                </figure>
                            </div>

                            <div class="card-body">
                                <h3><a class="text-inherit" href="{{route('blog.show', $next )}}">{{$next->title}}</a>
                                </h3>
                            </div>

                            <div class="card-footer border-0 pt-0">
                                <div class="media align-items-center">

                                    <div class="media-body d-flex justify-content-end text-muted font-size-1 ml-2">
                                        {{$next->created_at->format('M d')}}
                                    </div>
                                </div>
                            </div>
                        </article>
                        <!-- End Blog Card -->
                    </div>
                @endif


            </div>
        </div>
    </div>
    <!-- End Blog Card Section -->

@endsection
