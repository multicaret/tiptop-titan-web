@extends('layouts.admin')

@if(!is_null($address->id))
    @section('title', trans('strings.editing') .' - ' . trans('strings.user_address'))
@else
    @section('title', trans('strings.add_new_user_address'))
@endif

@push('styles')
    <link rel="stylesheet" href="/admin-assets/libs/quill/typography.css">
    <link rel="stylesheet" href="/admin-assets/libs/quill/editor.css">
@endpush

@section('content')

    <div class="mb-4">
        @if(!is_null($address->id))
            <h5>Editing User Address - {{ $address->name }}</h5>
        @else
            <h5>{{trans('strings.add_new_user_address')}}</h5>
        @endif
    </div>

    <form method="POST" enctype="multipart/form-data"
          @if(is_null($address->id))
          action="{{route('admin.users.addresses.store', ['user' => $user])}}"
          @else
          action="{{route('admin.users.addresses.update', ['user' => $user, 'address' => $address])}}"
        @endif
    >
        {{csrf_field()}}
        @if(!is_null($address->id))
            {{method_field('put')}}
        @else
            {{method_field('post')}}
        @endif
        <div class="row mb-4">
            <div class="col-md-12 mt-4">
                <div class="card card-outline-inverse">
                    <h4 class="card-header">Details</h4>
                    <div class="card-body row">
                        <div class="col-3">
                            <div class="form-group">
                                <label class="control-label">
                                    @lang('strings.kind')
                                    <b class="text-danger">*</b>
                                </label>
                                <multiselect
                                    :options="kinds"
                                    v-model="kind"
                                    track-by="id"
                                    label="title"
                                    select-label=""
                                    selected-label=""
                                    deselect-label=""
                                    placeholder=""
                                    autocomplete="false"
                                ></multiselect>
                                @error('kind')
                                <small class="form-text text-danger">
                                    {{$message}}
                                </small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            @component('admin.components.form-group', ['name' => 'alias', 'type' => 'text'])
                                @slot('label', trans('strings.address_title'))
                                @if(! is_null($address->id))
                                    @slot('value', $address->alias)
                                @endif
                            @endcomponent
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="control-label">
                                    @lang('strings.city')
                                    <b class="text-danger">*</b>
                                </label>
                                <multiselect
                                    :options="regions"
                                    v-model="address.region"
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
                                @error('city')
                                <small class="form-text text-danger">
                                    {{$message}}
                                </small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="control-label">
                                    Neighborhood
                                    <b class="text-danger">*</b>
                                </label>
                                <multiselect
                                    :options="cities"
                                    v-model="address.city"
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
                                @error('region')
                                <small class="form-text text-danger">
                                    {{str_replace('region', 'neighborhood', $message)}}
                                </small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            @component('admin.components.form-group', ['name' => 'address1', 'type' => 'textarea'])
                                @slot('label', 'Address')
                                @slot('value', $address->address1)
                                @slot('attributes', ['rows' => 4])
                            @endcomponent
                        </div>
                        <div class="col-md-6">
                            @component('admin.components.form-group', ['name' => 'notes', 'type' => 'textarea'])
                                @slot('label', 'Directions')
                                @slot('value', $address->notes)
                                @slot('attributes', ['rows' => 4])
                            @endcomponent
                        </div>
                        <div class="col-md-12">
                            @component('admin.components.form-group', ['name' => 'phone_number', 'type' => 'tel'])
                                @slot('label', trans('strings.phone_number'))
                                @if(! is_null($address->id))
                                    @slot('value', is_array($address->phones) && count($address->phones) ? $address->phones[0]: $address->phones)
                                @endif
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-outline-inverse mb-4">
            <h4 class="card-header">Location On Map</h4>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-12">
                                @component('admin.components.form-group', ['name' => 'latitude', 'type' => 'text'])
                                    @slot('label', 'Latitude')
                                    @slot('attributes', [
                                       'id' => 'latitude'
                                   ])
                                    @slot('value', $address->latitude)
                                @endcomponent
                            </div>
                            <div class="col-12">
                                @component('admin.components.form-group', ['name' => 'longitude', 'type' => 'text'])
                                    @slot('label', 'Longitude')
                                    @slot('attributes', [
                                       'id' => 'longitude'
                                   ])
                                    @slot('value', $address->longitude)
                                @endcomponent
                            </div>
                            <div class="col-12">
                                <a href="https://maps.google.com/?q={{$address->latitude}},{{$address->longitude}}"
                                   target="_blank">
                                    Open In Google Maps <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-6" style="height: 250px;">
                        <div id="gmaps-address" style="height: 100%; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <input type="hidden" name="region" :value="JSON.stringify(address.region)">
            <input type="hidden" name="city" :value="JSON.stringify(address.city)">
            <input type="hidden" name="kind" :value="JSON.stringify(kind)">
            <button class="btn btn-success" type="submit">{{trans('strings.submit')}}</button>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="https://maps.google.com/maps/api/js?key={{env('GOOGLE_MAPS_API')}}"></script>
    <script src="/admin-assets/libs/gmaps/gmaps.js"></script>
    {{--    <script src="/js/charts_gmaps.js"></script>--}}
    <script src="{{ asset('/admin-assets/libs/quill/quill.js') }}"></script>
    <script src="/admin-assets/libs/select2/select2.js"></script>
    <script>
        $(function () {
            $('.select2-link-types').select2({
                placeholder: 'Select link',
            });
        });
    </script>
    <script>
        $(function () {
            const lat = {!! json_encode(isset($address->latitude) ? $address->latitude: config('defaults.geolocation.latitude')) !!};
            const lng = {!! json_encode(isset($address->longitude)? $address->longitude : config('defaults.geolocation.longitude')) !!};
            latitude.value = lat;
            longitude.value = lng;
            const map = new GMaps({el: '#gmaps-address', lat: lat, lng: lng});
            const marker = map.addMarker({
                lat: lat,
                lng: lng,
                streetViewControl: false,
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
                address: @json($address),
                kinds: @json($kinds),
                kind: @json(!is_null($address->kind) ? $address->actualKind : null),
                regions: @json($regions),
                cities: [],
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
