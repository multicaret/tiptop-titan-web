@extends('layouts.admin')

@section('content')

    <div class="mb-4">
        @if(!is_null($brand->id))
            <h5>Editing Brand {{ $brand->title }}</h5>
        @else
            <h5>Add New Brand </h5>
        @endif
    </div>

    <form method="POST" enctype="multipart/form-data"
          @if(is_null($brand->id))
          action="{{route('admin.brands.store')}}"
          @else
          action="{{route('admin.brands.update',[$brand->id])}}"
        @endif
    >
        @csrf
        @if(!is_null($brand->id))
            {{method_field('put')}}
        @endif

        <div class="row mb-4">
            <div class="col-12 mb-4">
                <ul class="nav nav-tabs border-bottom-0">
                    @foreach(localization()->getSupportedLocales() as $key => $locale)
                        <li class="nav-item">
                            <a class="nav-link {{ $key == localization()->getDefaultLocale() ? 'active' : '' }}"
                               data-toggle="tab"
                               href="#title_{{$key}}">
                                <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                <span class="hidden-xs-down">{{$locale->native()}}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content card card-outline-inverse">
                    @foreach(localization()->getSupportedLocales() as $langKey => $locale)
                        <div
                            class="tab-pane {{ $langKey == localization()->getDefaultLocale() ? 'active' : '' }}"
                            id="title_{{$langKey}}">
                            <div class="card-body">
                                <div class="row p-t-20">
                                    <div class="col-md-12">
                                        @component('admin.components.form-group', ['name' => $langKey .'[title]', 'type' => 'text'])
                                            @slot('label', 'Brand')
                                        @if($langKey == localization()->getDefaultLocale())
                                                @slot('attributes', ['required'])
                                            @endif

                                            @if(! is_null($brand->id))
                                                @slot('value', optional($brand->translate($langKey))->title)
                                            @endif
                                        @endcomponent
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

            </div>


            <div class="col-md-6">
                <div class="card card-outline-inverse">
                    <h4 class="card-header">Logo</h4>
                    <div class="card-body">
                        <h5>Cover</h5>
                        @component('admin.components.form-group', ['name' => 'cover', 'type' => 'file'])
                            @slot('attributes', [
                                'class' => 'cover-uploader',
                                'accept' => '.jpg, .jpeg, .png, .bmp',
                                'dropzone' => 'media-list',
                                'data-fileuploader-listInput' => 'media-list',
                                'data-fileuploader-extensions' => 'jpg, jpeg, png, bmp',
                                'data-fileuploader-files' => json_encode($brand->getMediaForUploader('cover'), JSON_UNESCAPED_UNICODE),
                            ])
                        @endcomponent

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-outline-inverse">
                    <div class="card-body">
                        <h5>Status</h5>
                        @component('admin.components.form-group', ['name' => 'status', 'type' => 'select'])
                            @slot('label', trans('strings.status'))
                            @slot('options', \App\Models\Brand::getStatusesArray())
                            @slot('selected', $brand->status)
                        @endcomponent

                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-success" type="submit">Submit</button>
        <input type="hidden" name="unattached-media" class="deleted-file" value="">
    </form>

@endsection


