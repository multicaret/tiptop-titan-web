@extends('layouts.admin')
@section('title','Preferences')
@section('content')


    <div class="col-12">
        <h4 class="w-100 font-weight-bold py-3 mb-4">
            Preferences
        </h4>
    </div>
    <div class="d-flex col-xl-12 align-items-stretch">
        <div class="card d-flex w-100 mb-4">
            <div class="row no-gutters row-bordered h-100">
                @foreach($sections as $section)
                    <div class="d-flex col-sm-12 col-md-6 col-lg-6 align-items-center">

                        <a href="{{ route('admin.preferences.edit',$section) }}"
                           class="card-body media align-items-center text-body">
                            <i class="{{$section->icon}} fa-3x text-primary"></i>
                            <span class="media-body d-block ml-3">
                              <span class="text-big font-weight-bolder">{{$section->key}}</span>
                                <br>
                              <small class="text-muted">
                                  {!! $section->notes !!}
                              </small>
                            </span>
                        </a>

                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="d-flex col-xl-12 align-items-stretch">
        <div class="card d-flex w-100 mb-4">
            <div class="row no-gutters row-bordered h-100">
                <div class="d-flex col-sm-12 col-md-6 col-lg-6 align-items-center">
                    <a href="{{route('admin.preferences.adjust-trackers')}}"
                       class="card-body media align-items-center text-body">
                        <i class="lnr lnr-link display-4 d-block text-primary"></i>
                        <span class="media-body d-block ml-3">
                              <span class="text-big font-weight-bolder">{{trans('strings.deep_links')}}</span>
                                <br>
                              <small class="text-muted"></small>
                            </span>
                    </a>

                </div>
            </div>
        </div>
    </div>
@endsection
