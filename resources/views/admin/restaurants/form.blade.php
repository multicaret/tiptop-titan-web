@extends('layouts.admin')

@if(!is_null($chain->id))
    @section('title', trans('strings.editing') .' - ' . trans('strings.restaurant'))
@else
    @section('title', trans('strings.add_new') .' - ' . trans('strings.restaurant'))
@endif

@push('styles')
    <link rel="stylesheet" href="/admin-assets/libs/quill/typography.css">
    <link rel="stylesheet" href="/admin-assets/libs/quill/editor.css">
@endpush

@section('content')

    <div class="mb-4">
        @if(!is_null($chain->id))
            <h5>Editing Restaurant - {{ $chain->title }}</h5>
        @else
            <h5>{{trans('strings.add_new')}} Restaurant</h5>
        @endif
    </div>

    <form method="post" enctype="multipart/form-data"
          @if(is_null($chain->id))
          action="{{route('admin.restaurants.store',['type' => strtolower($typeName)])}}"
          @else
          action="{{route('admin.restaurants.update', ['type' => strtolower($typeName),$chain->uuid])}}"
        @endif
    >
        {{csrf_field()}}
        @if(!is_null($chain->id))
            {{method_field('put')}}
        @endif
        <div class="row mb-4">
            <div class="col-md-9">
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
                                                @if(! is_null($chain->id))
                                                    @slot('value', optional($chain->translate($langKey))->title)
                                                @endif
                                            @endcomponent
                                        </div>
                                        <div class="col-md-12">
                                            <x-admin.textarea :id="$langKey.'-description'"
                                                              :name="$langKey.'[description]'"
                                                              label="Description"
                                                              :content="optional($chain->translate($langKey))->description"/>
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
                @include('admin.branches.partials._branch-delivery-methods')
                <div class="col-md-12 mt-2">
                    <div class="card card-outline-inverse">
                        <h4 class="card-header">Details</h4>
                        <div class="card-body">
                            <div class="row">
                                @if($type == \App\Models\Branch::CHANNEL_FOOD_OBJECT)
                                    <div class="col-md-6 mt-3">
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
                                <div class="col-md-6 mt-3">
                                    @component('admin.components.form-group', ['name' => 'status', 'type' => 'select'])
                                        @slot('label', trans('strings.status'))
                                        @slot('options', \App\Models\Chain::getStatusesArray())
                                        @slot('selected', $chain->status)
                                    @endcomponent
                                </div>
                                <div class="col-md-4 mt-3">
                                    @component('admin.components.form-group', ['name' => 'primary_phone_number', 'type' => 'tel'])
                                        @slot('label', 'Primary phone number')
                                        @slot('value', $chain->primary_phone_number)
                                    @endcomponent
                                </div>
                                <div class="col-md-4 mt-3">
                                    @component('admin.components.form-group', ['name' => 'secondary_phone_number', 'type' => 'tel'])
                                        @slot('label', 'Secondary phone number')
                                        @slot('value', $chain->secondary_phone_number)
                                    @endcomponent
                                </div>
                                <div class="col-md-4 mt-3">
                                    @component('admin.components.form-group', ['name' => 'whatsapp_phone_number', 'type' => 'tel'])
                                        @slot('label', 'Whatsapp phone number')
                                        @slot('value', $chain->whatsapp_phone_number)
                                    @endcomponent
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('admin.branches.partials._branch-contacts')

                {{--<div class="col-md-12 mt-2">
                    <div class="card card-outline-inverse">
                        <h4 class="card-header">Style</h4>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    @component('admin.components.form-group', ['name' => 'primary_color', 'type' => 'color'])
                                        @slot('label', 'Primary color')
                                        @slot('value', is_null($chain->primary_color)? '#FEC63D' : $chain->primary_color)
                                    @endcomponent
                                </div>
                                <div class="col-md-6">
                                    @component('admin.components.form-group', ['name' => 'secondary_color', 'type' => 'color'])
                                        @slot('label', 'Secondary color')
                                        @slot('value', is_null($chain->secondary_color)? '#4E5155' : $chain->secondary_color)
                                    @endcomponent
                                </div>
                                <div class="col-md-6">
                                    @component('admin.components.form-group', ['name' => 'number_of_items_on_mobile_grid_view', 'type' => 'number'])
                                        @slot('label', 'Number of items on mobile grid view')
                                        @slot('value', $chain->number_of_items_on_mobile_grid_view)
                                    @endcomponent
                                </div>
                            </div>
                        </div>
                    </div>
                </div>--}}
            </div>
            <div class="col-md-3" style="margin-top: 2.2rem !important;">
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <div class="card card-outline-inverse">
                            <h4 class="card-header">Photos</h4>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <h5>Logo</h5>
                                        @component('admin.components.form-group', ['name' => 'logo', 'type' => 'file'])
                                            @slot('attributes', [
                                                'class' => 'cover-uploader',
                                                'accept' => '.jpg, .jpeg, .png, .bmp',
                                                'dropzone' => 'media-list',
                                                'data-fileuploader-listInput' => 'media-list',
                                                'data-fileuploader-extensions' => 'jpg, jpeg, png, bmp',
                                                'data-fileuploader-files' => json_encode($chain->getMediaForUploader('logo'), JSON_UNESCAPED_UNICODE),
                                            ])
                                        @endcomponent
                                    </div>
                                    <div class="col-12">
                                        <h5>Cover</h5>
                                        @component('admin.components.form-group', ['name' => 'cover', 'type' => 'file'])
                                            @slot('attributes', [
                                                'class' => 'cover-uploader',
                                                'accept' => '.jpg, .jpeg, .png, .bmp',
                                                'dropzone' => 'media-list',
                                                'data-fileuploader-listInput' => 'media-list',
                                                'data-fileuploader-extensions' => 'jpg, jpeg, png, bmp',
                                                'data-fileuploader-files' => json_encode($chain->getMediaForUploader('cover'), JSON_UNESCAPED_UNICODE),
                                            ])
                                        @endcomponent
                                    </div>

                                    {{--                            <div class="col-12">--}}
                                    {{--                                <h5>Gallery</h5>--}}
                                    {{--                                @component('admin.components.form-group', ['name' => 'gallery', 'type' => 'file'])--}}
                                    {{--                                    @slot('attributes', [--}}
                                    {{--                                        'label'=> 'Gallery',--}}
                                    {{--                                        'class' => 'images-uploader',--}}
                                    {{--                                        'accept' => '.jpg, .jpeg, .png, .bmp',--}}
                                    {{--                                        'dropzone' => 'media-list',--}}
                                    {{--                                        'data-fileuploader-listInput' => 'media-list',--}}
                                    {{--                                        'data-fileuploader-extensions' => 'jpg, jpeg, png, bmp',--}}
                                    {{--                                        'data-fileuploader-files' => !is_null($chain->id) && !is_null($gallery = $chain->getMediaForUploader('gallery'))? json_encode($gallery, JSON_UNESCAPED_UNICODE) : null,--}}
                                    {{--                                    ])--}}
                                    {{--                                @endcomponent--}}
                                    {{--                            </div>--}}
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
            <input type="hidden" name="unattached-media" class="deleted-file" value="">
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
    {{--
        <script>
            $(function () {
                $('.select2-regions').select2({
                    placeholder: 'Select regions',
                });
            });
        </script>
    --}}
    @include('admin.branches.partials._branch-js', ['chains' => []])
@endpush
