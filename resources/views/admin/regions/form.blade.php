@extends('layouts.admin')

@if(!is_null($region->id))
    @section('title', trans('strings.editing') .' - ' . trans('strings.regions'))
@else
    @section('title', trans('strings.add_new_region'))
@endif

@push('styles')
    <link rel="stylesheet" href="/admin-assets/libs/quill/typography.css">
    <link rel="stylesheet" href="/admin-assets/libs/quill/editor.css">
@endpush

@section('content')

    <div class="mb-4">
        @if(!is_null($region->id))
            <h5>Editing Region - {{ $region->name }}</h5>
        @else
            <h5>{{trans('strings.add_new')}} {{trans('strings.region')}}</h5>
        @endif
    </div>

    <form method="post" enctype="multipart/form-data"
          @if(is_null($region->id))
          action="{{route('admin.regions.store')}}"
          @else
          action="{{route('admin.regions.update', [$region->id])}}"
        @endif
    >
        {{csrf_field()}}
        @if(!is_null($region->id))
            {{method_field('put')}}
        @endif
        <div class="row mb-4">
            <div class="col-12">
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
                            id="name_{{$langKey}}">
                            <div class="card-body pb-0">
                                <div class="row p-t-20">
                                    <div class="col-md-12">
                                        @component('admin.components.form-group', ['name' => $langKey .'[name]', 'type' => 'text'])
                                            @slot('label', trans('strings.name'))

                                            @if(! is_null($region->id))
                                                @slot('value', optional($region->translate($langKey))->name)
                                            @endif
                                        @endcomponent
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                    {{--<div class="card-body pt-0">
                        <div class="row">
                            <div class="col-12">
                                @component('admin.components.form-group', ['name' => 'region_id', 'type' => 'select'])
                                    @slot('label', 'Region')
                                    @slot('options', $regions->pluck('name', 'id')->prepend('',''))
                                    @slot('attributes', [
                                        'class' => 'select2-regions w-100',
                                        'required',
                                    ])
                                    @slot('selected', $region->region_id)
                                @endcomponent
                            </div>
                        </div>
                    </div>--}}
                </div>
            </div>

        </div>
        <button class="btn btn-success" type="submit">{{trans('strings.submit')}}</button>
        <input type="hidden" name="unattached-media" class="deleted-file" value="">
    </form>

@endsection

@push('scripts')
    <script src="{{ asset('/admin-assets/libs/quill/quill.js') }}"></script>

    {{--<script>
        new Vue({
            el: '#vue-app',
            data: {
                regions: @json($regions),
            },
        })
    </script>
    <script src="/admin-assets/libs/select2/select2.js"></script>
    <script>
        $(function () {
            $('.select2-regions').select2({
                placeholder: 'Select regions',
            });
        });
    </script>--}}

@endpush
