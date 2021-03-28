@extends('layouts.admin')

@if(!is_null($product->id))
    @section('title', trans('strings.editing') .' - ' . trans('strings.chain'))
@else
    @section('title', trans('strings.add_new') .' - ' . trans('strings.chain'))
@endif

@push('styles')
    <link rel="stylesheet" href="/admin-assets/libs/quill/typography.css">
    <link rel="stylesheet" href="/admin-assets/libs/quill/editor.css">
@endpush

@section('content')

    <div class="mb-4">
        @if(!is_null($product->id))
            <h5>Editing Product - {{ $product->title }}</h5>
        @else
            <h5>{{trans('strings.add_new')}} Product</h5>
        @endif
    </div>

    <form method="post" enctype="multipart/form-data"
          @if(is_null($product->id))
          action="{{route('admin.products.store',['type' => strtolower($typeName)])}}"
          @else
          action="{{route('admin.products.update', ['type' => strtolower($typeName),$product->uuid])}}"
        @endif
    >
        {{csrf_field()}}
        @if(!is_null($product->id))
            {{method_field('put')}}
        @endif
        <div class="row mb-4">
            <div class="col-md-12">
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
                                id="title_{{$langKey}}">
                                <div class="card-body pb-0">
                                    <div class="row p-t-20">
                                        @foreach($translatedInputs as $input => $type)
                                            @if($type === 'editor')
                                                <div class="col-md-12">
                                                    <x-admin.textarea :id="$langKey.'-'. $input"
                                                                      :name="$langKey.'['.$input.']'"
                                                                      :label="$input"
                                                                      :content="optional($product->translate($langKey))->$input"/>
                                                </div>
                                            @else
                                                <div class="col-md-12">
                                                    @component('admin.components.form-group', ['name' => $langKey .'['.$input.']', 'type' => $type])
                                                        @slot('label', trans('strings.'. $input))
                                                        @if(! is_null($product->$input))
                                                            @slot('value', optional($product->translate($langKey))->$input)
                                                        @endif
                                                    @endcomponent
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                {{--                <div class="col-md-12 mt-2">--}}

                {{--                    <div class="card card-outline-inverse">--}}
                {{--                        <h4 class="card-header">Details</h4>--}}
                {{--                        <div class="card-body">--}}
                {{--                            <div class="row bg-light pt-3">--}}
                {{--                                <div class="col-6">--}}
                {{--                                    <div class="form-group">--}}
                {{--                                        <label class="control-label">--}}
                {{--                                            @lang('strings.region')--}}
                {{--                                        </label>--}}
                {{--                                                                                <multiselect--}}
                {{--                                                                                    :options="regions"--}}
                {{--                                                                                    v-model="chain.region"--}}
                {{--                                                                                    track-by="name"--}}
                {{--                                                                                    label="name"--}}
                {{--                                                                                    :searchable="true"--}}
                {{--                                                                                    :allow-empty="true"--}}
                {{--                                                                                    select-label=""--}}
                {{--                                                                                    selected-label=""--}}
                {{--                                                                                    deselect-label=""--}}
                {{--                                                                                    placeholder=""--}}
                {{--                                                                                    @select="retrieveCities"--}}
                {{--                                                                                    autocomplete="false"--}}
                {{--                                                                                ></multiselect>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                                <div class="col-6">--}}
                {{--                                    <div class="form-group">--}}
                {{--                                        <label class="control-label">--}}
                {{--                                            @lang('strings.city')--}}
                {{--                                        </label>--}}
                {{--                                                                                <multiselect--}}
                {{--                                                                                    :options="cities"--}}
                {{--                                                                                    v-model="chain.city"--}}
                {{--                                                                                    track-by="name"--}}
                {{--                                                                                    label="name"--}}
                {{--                                                                                    :searchable="true"--}}
                {{--                                                                                    :allow-empty="true"--}}
                {{--                                                                                    select-label=""--}}
                {{--                                                                                    selected-label=""--}}
                {{--                                                                                    deselect-label=""--}}
                {{--                                                                                    placeholder=""--}}
                {{--                                                                                                                            @select="retrieveNeighborhoods"--}}
                {{--                                                                                    autocomplete="false"--}}
                {{--                                                                                ></multiselect>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                            <div class="row">--}}
                {{--                                <div class="col-md-4 mt-3">--}}
                {{--                                    @component('admin.components.form-group', ['name' => 'primary_phone_number', 'type' => 'tel'])--}}
                {{--                                        @slot('label', 'Primary phone number')--}}
                {{--                                        @slot('value', $product->primary_phone_number)--}}
                {{--                                    @endcomponent--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                {{--<div class="col-md-12 mt-2">
                    <div class="card card-outline-inverse">
                        <h4 class="card-header">Style</h4>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    @component('admin.components.form-group', ['name' => 'primary_color', 'type' => 'color'])
                                        @slot('label', 'Primary color')
                                        @slot('value', is_null($product->primary_color)? '#FEC63D' : $product->primary_color)
                                    @endcomponent
                                </div>
                                <div class="col-md-6">
                                    @component('admin.components.form-group', ['name' => 'secondary_color', 'type' => 'color'])
                                        @slot('label', 'Secondary color')
                                        @slot('value', is_null($product->secondary_color)? '#4E5155' : $product->secondary_color)
                                    @endcomponent
                                </div>
                                <div class="col-md-6">
                                    @component('admin.components.form-group', ['name' => 'number_of_items_on_mobile_grid_view', 'type' => 'number'])
                                        @slot('label', 'Number of items on mobile grid view')
                                        @slot('value', $product->number_of_items_on_mobile_grid_view)
                                    @endcomponent
                                </div>
                            </div>
                        </div>
                    </div>
                </div>--}}
                <div class="col-md-12 mt-2">

                    <div class="card card-outline-inverse">
                        <h4 class="card-header">Details</h4>
                        <div class="card-body">
                            <div class="row">
                                @foreach($allInputs as $input => $type)
                                    @if($type !== 'select')
                                        <div class="col-6">
                                            @component('admin.components.form-group', ['name' => $input, 'type' => $type])
                                                @slot('label', trans('strings.'. $input))
                                                @if(! is_null($product->$input))
                                                    @slot('value', optional($product->translate($langKey))->$input)
                                                @endif
                                            @endcomponent
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="margin-top: 2.2rem !important;">
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <div class="card card-outline-inverse">
                            <h4 class="card-header">Photos</h4>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <h5>Cover</h5>
                                        @component('admin.components.form-group', ['name' => 'cover', 'type' => 'file'])
                                            @slot('attributes', [
                                                'class' => 'cover-uploader',
                                                'accept' => '.jpg, .jpeg, .png, .bmp',
                                                'dropzone' => 'media-list',
                                                'data-fileuploader-listInput' => 'media-list',
                                                'data-fileuploader-extensions' => 'jpg, jpeg, png, bmp',
                                                'data-fileuploader-files' => json_encode($product->getMediaForUploader('cover'), JSON_UNESCAPED_UNICODE),
                                            ])
                                        @endcomponent
                                    </div>

                                    <div class="col-12">
                                        <h5>Gallery</h5>
                                        @component('admin.components.form-group', ['name' => 'gallery', 'type' => 'file'])
                                            @slot('attributes', [
                                                'label'=> 'Gallery',
                                                'class' => 'images-uploader',
                                                'accept' => '.jpg, .jpeg, .png, .bmp',
                                                'dropzone' => 'media-list',
                                                'data-fileuploader-listInput' => 'media-list',
                                                'data-fileuploader-extensions' => 'jpg, jpeg, png, bmp',
                                                'data-fileuploader-files' => !is_null($product->id) && !is_null($gallery = $product->getMediaForUploader('gallery'))? json_encode($gallery, JSON_UNESCAPED_UNICODE) : null,
                                            ])
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            {{--            <input type="hidden" name="region" :value="JSON.stringify(chain.region)">--}}
            {{--            <input type="hidden" name="city" :value="JSON.stringify(chain.city)">--}}
            <input type="hidden" name="unattached-media" class="deleted-file" value="">
            <button class="btn btn-success" type="submit">{{trans('strings.submit')}}</button>
        </div>
    </form>

@endsection

@push('scripts')
    <script src="{{ asset('/admin-assets/libs/quill/quill.js') }}"></script>
    <script src="/admin-assets/libs/select2/select2.js"></script>

    <script>
        new Vue({
            el: '#vue-app',
            data: {
                product: @json($product),

            },
            mounted() {
                console.log('product');
            },
            methods: {},
        })
    </script>

@endpush
