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
          ref="main-form"
    >
        {{csrf_field()}}
        @if(!is_null($branch->id))
            {{method_field('put')}}
        @endif
        <div class="row mb-4">
            <div class="col-md-12">
                {{--<div class="col-12">
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
                </div>--}}
                <div class="col-md-12">
                    <div class="row">
                        @foreach(localization()->getSupportedLocales() as $langKey => $locale)
                            <div class="col-md-4 mt-4">
                                <div class="card card-outline-inverse">
                                    <h4 class="card-header">{{Str::upper($langKey)}}</h4>
                                    <div class="card-body row">
                                        <div class="col-md-12">
                                            @component('admin.components.form-group', ['name' => $langKey .'[title]', 'type' => 'text'])
                                                @slot('label', trans('strings.name'))
                                                @if(! is_null($branch->id))
                                                    @slot('value', optional($branch->translate($langKey))->title)
                                                @endif
                                            @endcomponent
                                        </div>
                                        <div class="col-md-12">
                                            {{--<x-admin.textarea :id="$langKey.'-description'"--}}
                                            {{--                  :name="$langKey.'[description]'"--}}
                                            {{--                  label="Description"--}}
                                            {{--                  :content="optional($branch->translate($langKey))->description"/>--}}

                                            @component('admin.components.form-group', ['name' => $langKey .'[description]', 'type' => 'textarea'])
                                                @slot('label', 'Description')
                                                @slot('attributes', [
                                                        'rows' => 5,
                                                        ])
                                                @if(! is_null($branch->id))
                                                    @slot('value', optional($branch->translate($langKey))->description)
                                                @endif
                                            @endcomponent
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
                                                    @lang('strings.city')
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
                                                    Neighborhood
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
                                <div class="col-6" style="height: 250px;">
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
                                <div class="col-md-6">
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
                                @if($type == \App\Models\Branch::TYPE_FOOD_OBJECT)
                                    <div class="col-6">
                                        @component('admin.components.form-group', ['name' => 'food_categories', 'type' => 'select'])
                                            @slot('label', 'Food categories')
                                            @slot('attributes', [
                                               'class' => 'select2-categories w-100',
                                               'multiple'
                                           ])
                                            @slot('options', $foodCategories->pluck('title','id')->prepend('',''))
                                            @slot('selected', $branch->foodCategories)
                                        @endcomponent
                                    </div>
                                @endif


                                <div class="col-6">
                                    @component('admin.components.form-group', ['name' => 'status', 'type' => 'select'])
                                        @slot('label', trans('strings.status'))
                                        @slot('options', \App\Models\Branch::getStatusesArray())
                                        @slot('selected', $branch->status)
                                    @endcomponent
                                </div>

                                <div class="col-md-6 {{$type == \App\Models\Branch::TYPE_FOOD_OBJECT ? '':'mt-3'}}">
                                    @component('admin.components.form-group', ['name' => 'primary_phone_number', 'type' => 'tel'])
                                        @slot('label', 'Primary phone number')
                                        @slot('value', $branch->primary_phone_number)
                                    @endcomponent
                                </div>
                                {{--<div class="col-md-4 mt-3">
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
                                </div>--}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="{{$type == \App\Models\Branch::TYPE_FOOD_OBJECT ? "col-md-6" : "col-md-12" }} mt-2">
                            <div class="card card-outline-inverse">
                                <h4 class="card-header">Tiptop Delivery
                                    <label class="switcher switcher-primary m-3">
                                        <input type="checkbox" class="switcher-input"
                                               name="has_tip_top_delivery" {{$branch->has_tip_top_delivery ? 'checked' : ''}}>
                                        <span class="switcher-indicator">
                                    <span class="switcher-yes">
                                        <span class="ion ion-md-checkmark"></span>
                                    </span>
                                      <span class="switcher-no">
                                        <span class="ion ion-md-close"></span>
                                      </span>
                                </span>
                                    </label></h4>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mt-3">
                                            @component('admin.components.form-group', ['name' => 'minimum_order', 'type' => 'number'])
                                                @slot('label', 'Minimum order')
                                                @slot('value', is_null($branch->minimum_order) ? 0 : $branch->minimum_order)
                                                @slot('attributes',['step'=>1,'min'=>1])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            @component('admin.components.form-group', ['name' => 'fixed_delivery_fee', 'type' => 'number'])
                                                @slot('label', 'Fixed delivery fee')
                                                @slot('value', is_null($branch->fixed_delivery_fee) ? 0 : $branch->fixed_delivery_fee)
                                                @slot('attributes',['step'=>1,'min'=>1])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            @component('admin.components.form-group', ['name' => 'under_minimum_order_delivery_fee', 'type' => 'number'])
                                                @slot('label', 'Under minimum order delivery fee')
                                                @slot('value', is_null($branch->under_minimum_order_delivery_fee) ? 0 : $branch->under_minimum_order_delivery_fee)
                                                @slot('attributes',['step'=>1,'min'=>1])
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($type == \App\Models\Branch::TYPE_FOOD_OBJECT)
                            <div class="col-md-6 mt-2">
                                <div class="card card-outline-inverse">
                                    <h4 class="card-header">Restaurant Delivery
                                        <label class="switcher switcher-primary m-3">
                                            <input type="checkbox" class="switcher-input"
                                                   name="has_restaurant_delivery" {{$branch->has_restaurant_delivery ? 'checked' : ''}}>
                                            <span class="switcher-indicator">
                                    <span class="switcher-yes">
                                        <span class="ion ion-md-checkmark"></span>
                                    </span>
                                      <span class="switcher-no">
                                        <span class="ion ion-md-close"></span>
                                      </span>
                                </span>
                                        </label></h4>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mt-3">
                                                @component('admin.components.form-group', ['name' => 'restaurant_minimum_order', 'type' => 'number'])
                                                    @slot('label', 'Minimum order')
                                                    @slot('value', is_null($branch->restaurant_minimum_order) ? 0 : $branch->restaurant_minimum_order )
                                                    @slot('attributes',['step'=>1,'min'=>1])
                                                @endcomponent
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                @component('admin.components.form-group', ['name' => 'restaurant_fixed_delivery_fee', 'type' => 'number'])
                                                    @slot('label', 'Fixed delivery fee')
                                                    @slot('value', is_null($branch->restaurant_fixed_delivery_fee) ? 0 : $branch->restaurant_fixed_delivery_fee)
                                                    @slot('attributes',['step'=>1,'min'=>1])
                                                @endcomponent
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                @component('admin.components.form-group', ['name' => 'restaurant_under_minimum_order_delivery_fee', 'type' => 'number'])
                                                    @slot('label', 'Under minimum order delivery fee')
                                                    @slot('value', is_null($branch->restaurant_under_minimum_order_delivery_fee) ? 0 : $branch->restaurant_under_minimum_order_delivery_fee)
                                                    @slot('attributes',['step'=>1,'min'=>1])
                                                @endcomponent
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-12 mt-2">
                    <div id="accordion">
                        <div class="card mb-2">
                            <a class="text-body" data-toggle="collapse" href="#accordion-1">
                                <h4 class="card-header">Contacts</h4>
                            </a>
                            <div id="accordion-1" class="collapse show" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <table class="table card-table">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Phone Number</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody v-for="(contactDetail,index) in contactDetails">
                                                    <tr>
                                                        <th scope="row">@{{index+1}}</th>
                                                        <td>
                                                            <input type="text" v-model="contactDetail.name"
                                                                   class="form-control" name="contact-name">
                                                            {{--                                                            <small>@{{}}</small>--}}
                                                        </td>
                                                        <td>
                                                            <input type="text" v-model="contactDetail.email"
                                                                   class="form-control" name="contact-email">
                                                        </td>
                                                        <td>
                                                            <input type="text" v-model="contactDetail.phone"
                                                                   class="form-control" name="contact-phone">
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-danger text-white"
                                                               @click="removeItem(contactDetail.id)">
                                                                <i class="fa fa-trash-alt fa-sm"></i>
                                                            </a></td>
                                                    </tr>
                                                    </tbody>
                                                    <tr>
                                                        <td>
                                                            <a class="btn btn-primary" @click="addNewContact">
                                                                <i class="fa fa-plus"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <input type="hidden" name="region" :value="JSON.stringify(branch.region)">
            <input type="hidden" name="city" :value="JSON.stringify(branch.city)">
            <input type="hidden" name="chain" :value="JSON.stringify(branch.chain)">
            <input type="hidden" name="contactDetails" :value="JSON.stringify(contactDetails)">
            <input type="hidden" name="longitude" id="longitude">
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="unattached-media" class="deleted-file" value="">
            <div class="col-md-12" v-if="formErrorMessage">
                <p class="text-danger text-capitalize">@{{ formErrorMessage }}</p>
            </div>
            <button class="btn btn-success" type="submit"
                    @click="submitButton($event)">{{trans('strings.submit')}}</button>
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
            $('.select2-categories').select2({
                placeholder: 'Select food categories',
            });
            const lat = {!! json_encode(isset($branch->latitude) ? $branch->latitude: config('defaults.geolocation.latitude')) !!};
            const lng = {!! json_encode(isset($branch->longitude)? $branch->longitude : config('defaults.geolocation.longitude')) !!};
            latitude.value = lat;
            longitude.value = lng;
            const map = new GMaps({el: '#gmaps-branch', lat: lat, lng: lng});
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
                branch: @json($branch),
                regions: @json($regions),
                foodCategories: @json($foodCategories),
                cities: [],
                chains: @json($chains),
                contactDetails: @json($contacts),
                contactDetail: {
                    name: '',
                    email: '',
                    phone: ''
                },
                validationData: [],
                formErrorMessage: null,
                selectedRegion: null,
            },
            watch: {
                branch: {
                    handler: function (val) {
                        if (!this.selectedRegion || this.selectedRegion.id != val.region.id) {
                            this.selectedRegion = val.region;
                            if (this.branch.city != null) {
                                this.branch.city = null
                            }
                        }
                    },
                    deep: true,
                }
            },
            methods: {
                retrieveCities: function (region) {
                    axios.post(window.App.domain + `/ajax/countries/${region.country_id}/regions/${region.id}/cities`)
                        .then((res) => {
                            this.cities = res.data;
                        });
                },
                addNewContact: function () {
                    this.contactDetails.push(JSON.parse(JSON.stringify(this.contactDetail)))

                },
                removeItem: function (id) {
                    this.contactDetails.splice(this.contactDetails.indexOf(id), 1);

                },
                submitButton(e) {
                    if (this.contactDetails.length) {
                        let validationData = this.validationData;
                        let name = false
                        let phone = false
                        const titleElement = this.$refs['main-form'].elements.namedItem('en[title]');
                        console.log("titleElement");
                        console.log(titleElement);
                        this.contactDetails.forEach(function (element) {
                            name = element.name.length
                            phone = element.phone.length
                        })
                        validationData[0] = {'Name': name};
                        validationData[1] = {'Phone': phone};
                        for (let i = 0; i < validationData.length; i++) {
                            const tmpItem = validationData[i], inputLabel = Object.keys(tmpItem)[0];
                            if (!tmpItem[inputLabel]) {
                                this.setErrorMessage(`${inputLabel} is required.`);
                                break;
                            }
                        }
                        if (!!this.formErrorMessage) {
                            e.preventDefault();
                        } else {
                            this.$refs['main-form'].submit();
                        }
                    } else {
                        this.$refs['main-form'].submit();
                    }
                },
                setErrorMessage(msg) {
                    this.formErrorMessage = msg;
                    setTimeout(_ => {
                        this.formErrorMessage = null;
                    }, 2500);
                },
            },
        })
    </script>

@endpush
