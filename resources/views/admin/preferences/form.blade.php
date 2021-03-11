@extends('layouts.admin')
@section('title', $section->key)

@section('content')

    <form action="{{route('admin.preferences.update', $section)}}"
          method="POST" enctype="multipart/form-data">
        {{csrf_field()}}
        {{method_field('put')}}

        <div class="row">
            <div class="col-12 mb-3">
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
                                            @slot('label', 'Title')
                                            @if($langKey == localization()->getCurrentLocale())
                                                @slot('attributes', ['required'])
                                            @endif
{{--                                            @slot('value', optional($post->translate($langKey))->title)--}}
                                        @endcomponent
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-12">
            <button class="btn btn-success" type="submit">Submit</button>
        </div>
    </form>

@endsection
