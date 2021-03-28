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
                                    <div class="row p-t-20 pt-2">
                                        @foreach($translatedInputs as $input => $type)
                                            @if($type === 'editor')
                                                <div class="col-md-6">
                                                    <x-admin.textarea :id="$langKey.'-'. $input"
                                                                      :name="$langKey.'['.$input.']'"
                                                                      :label="$input"
                                                                      :content="optional($product->translate($langKey))->$input"/>
                                                </div>
                                            @else
                                                <div class="col-md-{{$input === 'title' ? '12' : '6'}}">
                                                    @component('admin.components.form-group', ['name' => $langKey .'['.$input.']', 'type' => $type])
                                                        @slot('label', trans('strings.'. $input))
                                                        @if(! is_null($product->id))
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
                <div class="col-md-12 mt-2">

                    <div class="card card-outline-inverse">
                        <h4 class="card-header">Selectors</h4>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="control-label">
                                            @lang('strings.chain')
                                        </label>
                                        <multiselect
                                            :options="chains"
                                            v-model="product.chain"
                                            track-by="id"
                                            label="title"
                                            name="chain_id"
                                            :searchable="true"
                                            :allow-empty="true"
                                            select-label=""
                                            selected-label=""
                                            deselect-label=""
                                            placeholder=""
                                            @select="resetBranch"
                                            autocomplete="false"
                                        ></multiselect>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="control-label">
                                            @lang('strings.branch')
                                        </label>
                                        <multiselect
                                            :options="branches"
                                            v-model="product.branch"
                                            track-by="id"
                                            label="title"
                                            name="branch_id"
                                            :searchable="true"
                                            :allow-empty="true"
                                            select-label=""
                                            selected-label=""
                                            deselect-label=""
                                            placeholder=""
                                            {{--                                            @select="retrieveCities"--}}
                                            autocomplete="false"
                                        ></multiselect>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="control-label">
                                            @lang('strings.categories')
                                        </label>
                                        <multiselect
                                            :options="categories"
                                            v-model="selectedCategories"
                                            track-by="id"
                                            label="title"
                                            name="categories"
                                            :searchable="true"
                                            :multiple="true"
                                            :allow-empty="true"
                                            select-label=""
                                            selected-label=""
                                            deselect-label=""
                                            placeholder=""
                                            autocomplete="false"
                                        ></multiselect>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="control-label">
                                            @lang('strings.units')
                                        </label>
                                        <multiselect
                                            :options="units"
                                            v-model="selectedUnit"
                                            track-by="id"
                                            label="title"
                                            name="unit_id"
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
                            </div>
                        </div>
                    </div>
                </div>

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
                                                @if(! is_null($product->id))
                                                    @slot('value',$type === config('defaults.db_column_types.datetime') ? Carbon\Carbon::parse($product->$input)  : $product->$input)
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
            <div class="col-md-3" style="margin-top: 2.2rem !important;">
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <div class="card card-outline-inverse">
                            <h4 class="card-header">Cover</h4>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        {{--                                        <h5>Cover</h5>--}}
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-2">
                <div class="card card-outline-inverse">
                    <h4 class="card-header">Gallery</h4>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                {{--                                        <h5>Gallery</h5>--}}
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

        <div class="col-md-12">
            <input type="hidden" name="chain_id" :value="JSON.stringify(selectedChain)">
            <input type="hidden" name="branch_id" :value="JSON.stringify(selectedBranch)">
            <input type="hidden" name="categories" :value="JSON.stringify(selectedCategories)">
            <input type="hidden" name="unit_id" :value="JSON.stringify(selectedUnit)">
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
                chains: @json($chains),
                selectedChain: null,
                branches: @json($branches),
                selectedBranch: null,
                categories: @json($categories),
                selectedCategories: null,
                units: @json($units),
                selectedUnit: null,
                allInputs: @json($allInputs)
            },
            beforeMount() {
                console.log(this.product);
            },
            methods: {
                resetBranch: function () {
                    console.log('resetBranch');
                }
            },
        })
    </script>

@endpush
