@extends('layouts.admin')

@if(!is_null($slide->id))
    @section('title', trans('strings.editing') .' - ' . trans('strings.slides'))
@else
    @section('title', trans('strings.add_new_slide'))
@endif

@push('styles')
    <link rel="stylesheet" href="/admin-assets/libs/quill/typography.css">
    <link rel="stylesheet" href="/admin-assets/libs/quill/editor.css">
@endpush

@section('content')

    <div class="mb-4">
        @if(!is_null($slide->id))
            <h5>Editing Slide - {{ $slide->name }}</h5>
        @else
            <h5>{{trans('strings.add_new')}} {{trans('strings.slide')}}</h5>
        @endif
    </div>

    <form method="POST" enctype="multipart/form-data"
          @if(is_null($slide->id))
          action="{{route('admin.slides.store')}}"
          @else
          action="{{route('admin.slides.update', $slide)}}"
        @endif
    >
        {{csrf_field()}}
        @if(!is_null($slide->id))
            {{method_field('put')}}
        @endif
        <div class="row mb-4">
            <div class="col-md-12 mt-4">
                <div class="card card-outline-inverse">
                    <h4 class="card-header">Info</h4>
                    <div class="card-body row">
                        <div class="col-md-12">
                            @component('admin.components.form-group', ['name' => 'title', 'type' => 'text'])
                                @slot('label', trans('strings.title'))
                                @if(! is_null($slide->id))
                                    @slot('value', $slide->title)
                                @endif
                            @endcomponent
                        </div>
                        <div class="col-md-12">
                            @component('admin.components.form-group', ['name' => 'description', 'type' => 'textarea'])
                                @slot('label', 'Description')
                                @slot('value', $slide->description)
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>

            @foreach(localization()->getSupportedLocales() as $langKey => $locale)
                <div class="col-md-4 mt-4">
                    <div class="card card-outline-inverse">
                        <h4 class="card-header">{{Str::upper($langKey)}}</h4>
                        <div class="card-body row">
                            <div class="col-md-12">
                                @component('admin.components.form-group', ['name' => $langKey .'[alt_tag]', 'type' => 'text'])
                                    @slot('label', trans('strings.alt-tag'))
                                    @slot('attributes',['required'])
                                    @if(! is_null($slide->id))
                                        @slot('value', optional($slide->translate($langKey))->alt_tag)
                                    @endif
                                @endcomponent
                            </div>
                            <div class="col-md-12">
                                <p class="">@lang('strings.cover')</p>
                                <div>
                                    @component('admin.components.form-group', ['name' => $langKey .'[image]', 'type' => 'file'])
                                        @slot('attributes', [
                                            'class' => 'cover-uploader',
                                            'accept' => '.jpg, .jpeg, .png, .bmp',
                                            'dropzone' => 'media-list',
                                            'data-fileuploader-listInput' => 'media-list',
                                            'data-fileuploader-extensions' => 'jpg, jpeg, png, bmp',
                                            'data-fileuploader-files' => json_encode(optional($slide->translate($langKey))->getMediaForUploader('image'), JSON_UNESCAPED_UNICODE),
                                        ])
                                    @endcomponent
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach


            <div class="col-md-12 mt-4">
                <div class="card card-outline-inverse">
                    <h4 class="card-header">Details</h4>
                    <div class="card-body row">

                        <div class="col-6">
                            <div class="form-group">
                                <label class="control-label">
                                    @lang('strings.city')
                                </label>
                                <multiselect
                                    :options="regions"
                                    v-model="slide.region"
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
                        <div class="col-6">
                            <div class="form-group">
                                <label class="control-label">
                                    Neighborhood
                                </label>
                                <multiselect
                                    :options="cities"
                                    v-model="slide.city"
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
                        <div class="col-6">
                            @component('admin.components.form-group', ['name' =>'begins_at', 'type' => 'datetime-local'])
                                @slot('label', 'Begins At')
                                @slot('value', \Carbon\Carbon::parse($slide->begins_at))
                                @slot('attributes',[])
                            @endcomponent
                        </div>
                        <div class="col-6">
                            @component('admin.components.form-group', ['name' =>'expires_at', 'type' => 'datetime-local'])
                                @slot('label', 'Expires At')
                                @slot('value', \Carbon\Carbon::parse($slide->expires_at))
                                @slot('attributes',[])
                            @endcomponent
                        </div>
                        <div class="col-6">
                            @component('admin.components.form-group', ['name' => 'link_type', 'type' => 'select'])
                                @slot('label', trans('strings.link_type'))
                                @slot('options', $linkTypes)
                                @slot('attributes', [
                                    'class' => 'select2-link-types w-100',
                                    'required',
                                ])
                                @slot('selected', $slide->link_type)
                            @endcomponent
                        </div>
                        <div class="col-md-6">
                            @component('admin.components.form-group', ['name' => 'link_value', 'type' => 'text'])
                                @slot('label', trans('strings.link_value'))
                                @slot('attributes', ['required', 'placeholder' => 'Google.com'])
                                @if(! is_null($slide->id))
                                    @slot('value', $slide->link_value)
                                @endif
                            @endcomponent
                        </div>
                        <div class="col-3">
                            @component('admin.components.form-group', ['name' => 'status', 'type' => 'select'])
                                @slot('label', trans('strings.status'))
                                @slot('options', \App\Models\Slide::getStatusesArray())
                                @slot('selected', $slide->status)
                            @endcomponent
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <button class="btn btn-success" type="submit">{{trans('strings.submit')}}</button>
        <div class="col-md-12">
            <input type="hidden" name="unattached-media" class="deleted-file" value="">
            <input type="hidden" name="region" :value="JSON.stringify(slide.region)">
            <input type="hidden" name="city" :value="JSON.stringify(slide.city)">
        </div>
    </form>

@endsection

@push('scripts')
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
        new Vue({
            el: '#vue-app',
            data: {
                slide: @json($slide),
                regions: @json($regions),
                cities: [],
                selectedRegion: null
            },
            watch: {
                slide: {
                    handler: function (val) {
                        if (!this.selectedRegion || this.selectedRegion.id != val.region.id) {
                            this.selectedRegion = val.region;
                            if (this.slide.city != null) {
                                this.slide.city = null
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
            },
        })
    </script>
@endpush
