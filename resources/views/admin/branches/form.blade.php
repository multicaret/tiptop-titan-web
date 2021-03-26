@extends('layouts.admin')

@if(!is_null($branch->id))
    @section('title', trans('strings.editing') .' - ' . trans('strings.branch'))
@else
    @section('title', trans('strings.add_new') .' - ' . trans('strings.branch'))
@endif

@push('styles')
    <link rel="stylesheet" href="/admin-assets/libs/quill/typography.css">
    <link rel="stylesheet" href="/admin-assets/libs/quill/editor.css">
@endpush

@section('content')

    <div class="mb-4">
        @if(!is_null($branch->id))
            <h5>Editing Branch - {{ $branch->title }}</h5>
        @else
            <h5>{{trans('strings.add_new')}} Branch</h5>
        @endif
    </div>

    <form method="post" enctype="multipart/form-data"
          @if(is_null($branch->id))
          action="{{route('admin.branches.store',['type'=> request()->type])}}"
          @else
          action="{{route('admin.branches.update',['type'=> request()->type,$branch->uuid])}}"
        @endif
    >
        {{csrf_field()}}
        @if(!is_null($branch->id))
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
                                        <div class="col-md-12">
                                            @component('admin.components.form-group', ['name' => $langKey .'[title]', 'type' => 'text'])
                                                @slot('label', trans('strings.name'))
                                                @if(! is_null($branch->id))
                                                    @slot('value', optional($branch->translate($langKey))->title)
                                                @endif
                                            @endcomponent
                                        </div>
                                        <div class="col-md-12">
                                            <x-admin.textarea :id="$langKey.'-description'"
                                                              :name="$langKey.'[description]'"
                                                              label="Description"
                                                              :content="optional($branch->translate($langKey))->description"/>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-md-12 mt-2">
                    <div class="card card-outline-inverse">
                        <h4 class="card-header">Address</h4>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="control-label">
                                                    @lang('strings.region')
                                                </label>
                                                <multiselect
                                                    :options="regions"
                                                    v-model="branch.region"
                                                    track-by="name"
                                                    label="name"
                                                    :searchable="true"
                                                    :allow-empty="true"
                                                    select-label=""
                                                    selected-label=""
                                                    deselect-label=""
                                                    placeholder=""
                                                    @select="retrieveCities"
                                                    autocomplete="false"
                                                ></multiselect>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="control-label">
                                                    @lang('strings.city')
                                                </label>
                                                <multiselect
                                                    :options="cities"
                                                    v-model="branch.city"
                                                    track-by="name"
                                                    label="name"
                                                    :searchable="true"
                                                    :allow-empty="true"
                                                    select-label=""
                                                    selected-label=""
                                                    deselect-label=""
                                                    placeholder=""
                                                    {{--                                        @select="retrieveNeighborhoods"--}}
                                                    autocomplete="false"
                                                ></multiselect>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div id="gmaps-branch" style="height: 100%; width: 100%;"></div>
                                    <div id="gmaps-branch" style="height: 100%; width: 100%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="card card-outline-inverse">
                        <h4 class="card-header">Details</h4>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">
                                            Chain
                                        </label>
                                        <multiselect
                                            :options="chains"
                                            v-model="branch.chain"
                                            track-by="title"
                                            label="title"
                                            :searchable="true"
                                            :allow-empty="true"
                                            select-label=""
                                            selected-label=""
                                            deselect-label=""
                                            placeholder=""
                                            autocomplete="false"
                                        ></multiselect>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-3">
                                    @component('admin.components.form-group', ['name' => 'minimum_order', 'type' => 'number'])
                                        @slot('label', 'Minimum order')
                                        @slot('value', $branch->minimum_order)
                                        @slot('attributes',['step'=>1,'min'=>1])
                                    @endcomponent
                                </div>
                                <div class="col-md-4 mt-3">
                                    @component('admin.components.form-group', ['name' => 'under_minimum_order_delivery_fee', 'type' => 'number'])
                                        @slot('label', 'Under minimum order delivery fee')
                                        @slot('value', $branch->under_minimum_order_delivery_fee)
                                        @slot('attributes',['step'=>1,'min'=>1])
                                    @endcomponent
                                </div>
                                <div class="col-md-4 mt-3">
                                    @component('admin.components.form-group', ['name' => 'fixed_delivery_fee', 'type' => 'number'])
                                        @slot('label', 'Fixed delivery fee')
                                        @slot('value', $branch->fixed_delivery_fee)
                                        @slot('attributes',['step'=>1,'min'=>1])
                                    @endcomponent
                                </div>

                                <div class="col-md-4 mt-3">
                                    @component('admin.components.form-group', ['name' => 'primary_phone_number', 'type' => 'tel'])
                                        @slot('label', 'Primary phone number')
                                        @slot('value', $branch->primary_phone_number)
                                    @endcomponent
                                </div>
                                <div class="col-md-4 mt-3">
                                    @component('admin.components.form-group', ['name' => 'secondary_phone_number', 'type' => 'tel'])
                                        @slot('label', 'Secondary phone number')
                                        @slot('value', $branch->secondary_phone_number)
                                    @endcomponent
                                </div>
                                <div class="col-md-4 mt-3">
                                    @component('admin.components.form-group', ['name' => 'whatsapp_phone_number', 'type' => 'tel'])
                                        @slot('label', 'Whatsapp phone number')
                                        @slot('value', $branch->whatsapp_phone_number)
                                    @endcomponent
                                </div>
                                <div class="col-12">
                                    @component('admin.components.form-group', ['name' => 'status', 'type' => 'select'])
                                        @slot('label', trans('strings.status'))
                                        @slot('options', \App\Models\Branch::getStatusesArray())
                                        @slot('selected', $branch->status)
                                    @endcomponent
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--<div class="col-md-3" style="margin-top: 2.2rem !important;">
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
                                                'data-fileuploader-files' => json_encode($branch->getMediaForUploader('cover'), JSON_UNESCAPED_UNICODE),
                                            ])
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>--}}
        </div>
        <div class="col-md-12">
            <input type="hidden" name="region" :value="JSON.stringify(branch.region)">
            <input type="hidden" name="city" :value="JSON.stringify(branch.city)">
            <input type="hidden" name="chain" :value="JSON.stringify(branch.chain)">
            <input type="hidden" name="longitude" id="longitude">
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="unattached-media" class="deleted-file" value="">
            <button class="btn btn-success" type="submit">{{trans('strings.submit')}}</button>
        </div>
    </form>

@endsection

@push('scripts')
    <script src="{{ asset('/admin-assets/libs/quill/quill.js') }}"></script>
    <script src="/admin-assets/libs/select2/select2.js"></script>
    <script src="https://maps.google.com/maps/api/js?key={{env('GOOGLE_MAPS_API')}}"></script>
    <script src="/admin-assets/libs/gmaps/gmaps.js"></script>
    {{--    <script src="/js/charts_gmaps.js"></script>--}}

    <script>
        $(function () {
            const lat = {!! json_encode(isset($branch->latitude) ? $branch->latitude: config('defaults.geolocation.latitude')) !!};
            const lng = {!! json_encode(isset($branch->longitude)? $branch->longitude : config('defaults.geolocation.longitude')) !!};
            latitude.value = lat;
            longitude.value = lng;
            const map = new GMaps({el: '#gmaps-branch', lat: lat, lng: lng});
            const marker = map.addMarker({
                lat: lat,
                lng: lng,
                draggable: true,
            });
            marker.addListener('dragend', function () {
                latitude.value = marker.getPosition().lat();
                longitude.value = marker.getPosition().lng();
            });
        });

        new Vue({
            el: '#vue-app',
            data: {
                branch: @json($branch),
                regions: @json($regions),
                cities: [],
                chains: @json($chains),
            },
            methods: {
                retrieveCities: function (region) {
                    axios.post(window.App.domain + `/ajax/countries/${region.country_id}/regions/${region.id}/cities`)
                        .then((res) => {
                            this.cities = res.data;
                        });
                },
            },
        })
    </script>

@endpush
