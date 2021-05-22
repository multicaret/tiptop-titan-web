@php
    $isGrocery = $product->type == \App\Models\Product::CHANNEL_GROCERY_OBJECT
@endphp

@extends('layouts.admin')

@if(!is_null($product->id))
    @section('title', 'Editing a product')
@else
    @section('title', 'Add new product')
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
          action="{{route('admin.products.store',[
                'type' => strtolower($typeName),
                'only-for-chains' => request()->has('only-for-chains') && request()->input('only-for-chains'),
            ])}}"
          @else
          action="{{route('admin.products.update', [
                'type' => strtolower($typeName),$product->uuid,
                'only-for-chains' => request()->has('only-for-chains') && request()->input('only-for-chains'),
            ])}}"
        @endif
    >
        {{csrf_field()}}
        @if(!is_null($product->id))
            {{method_field('put')}}
        @endif
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="col-12">
                    <div class="row">
                        @foreach(localization()->getSupportedLocales() as $langKey => $locale)
                            <div class="col-md-4 mt-4">
                                <div class="card card-outline-inverse">
                                    <h4 class="card-header">{{Str::upper($langKey)}}</h4>
                                    <div class="card-body row">
                                        <div class="col-md-12">
                                            @component('admin.components.form-group', ['name' => $langKey .'[title]', 'type' => 'text'])
                                                @slot('label', trans('strings.title'))
                                                @if(! is_null($product->id))
                                                    @slot('value', optional($product->translate($langKey))->title)
                                                @endif
                                            @endcomponent
                                        </div>
                                        <div class="col-md-12">
                                            @component('admin.components.form-group', ['name' => $langKey .'[excerpt]', 'type' => 'textarea'])
                                                @slot('label', 'Excerpt')
                                                @slot('attributes', [
                                                        'rows' => 2,
                                                        ])
                                                @if(! is_null($product->id))
                                                    @slot('value', optional($product->translate($langKey))->excerpt)
                                                @endif
                                            @endcomponent
                                        </div>
                                        <div class="col-md-12">
                                            @component('admin.components.form-group', ['name' => $langKey .'[description]', 'type' => 'textarea'])
                                                @slot('label', 'Description')
                                                @slot('attributes', [
                                                        'rows' => 2,
                                                        ])
                                                @if(! is_null($product->id))
                                                    @slot('value', optional($product->translate($langKey))->description)
                                                @endif
                                            @endcomponent
                                        </div>
                                        <div class="col-md-12">
                                            @component('admin.components.form-group', ['name' => $langKey .'[custom_banner_text]', 'type' => 'text'])
                                                @slot('label', trans('strings.custom_banner_text'))
                                                @if(! is_null($product->id))
                                                    @slot('value', optional($product->translate($langKey))->custom_banner_text)
                                                @endif
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

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
                                @if(!request()->has('branch_id'))
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="control-label">
                                                @lang('strings.chain') <b class="text-danger">*</b>
                                            </label>
                                            <multiselect
                                                :options="chains"
                                                v-model="product.chain"
                                                track-by="id"
                                                label="title"
                                                :searchable="true"
                                                :allow-empty="true"
                                                select-label=""
                                                selected-label=""
                                                deselect-label=""
                                                placeholder=""
                                                @input="getBranches"
                                                @select="selectChain"
                                                autocomplete="false"
                                                required
                                            ></multiselect>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        @if(!request()->has('only-for-chains'))
                                            <div class="form-group">
                                                <label class="control-label">
                                                    @lang('strings.branch') <b class="text-danger">*</b>
                                                </label>
                                                <multiselect
                                                    :options="branches"
                                                    v-model="product.branch"
                                                    track-by="id"
                                                    label="title"
                                                    :searchable="true"
                                                    :allow-empty="true"
                                                    select-label=""
                                                    selected-label=""
                                                    deselect-label=""
                                                    :clear-on-select="false"
                                                    :preselect-first="true"
                                                    @if(!$isGrocery)
                                                    @input="getMenuCategories"
                                                    @select="selectBranch"
                                                    @endif
                                                    placeholder=""
                                                    autocomplete="false"
                                                    required
                                                ></multiselect>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                <div
                                    class="col-{{$isGrocery ? "6" : "12"}}">
                                    @if($isGrocery)
                                        <div class="form-group">
                                            <label class="control-label">
                                                {{ trans('strings.categories') }}
                                                &nbsp;<b class="text-danger">*</b>
                                            </label>
                                            <multiselect
                                                :options="categories"
                                                v-model="product.categories"
                                                track-by="id"
                                                label="title"
                                                name="categories"
                                                :multiple="isGrocery"
                                                :searchable="true"
                                                :allow-empty="true"
                                                select-label=""
                                                selected-label=""
                                                deselect-label=""
                                                placeholder=""
                                                autocomplete="false"
                                                required
                                            ></multiselect>
                                            @error('categories')
                                            <span class="text-danger">
                                                    {{$message}}
                                                </span>
                                            @enderror
                                        </div>
                                    @else
                                        {{--                                        @{{product}}--}}
                                        <div class="form-group">
                                            <label class="control-label">
                                                {{ trans('strings.menu-category') }}
                                                &nbsp;<b class="text-danger">*</b>
                                            </label>
                                            <multiselect
                                                :options="categories"
                                                v-model="product.category"
                                                track-by="id"
                                                label="title"
                                                name="category"
                                                :multiple="isGrocery"
                                                :searchable="true"
                                                :allow-empty="true"
                                                select-label=""
                                                selected-label=""
                                                deselect-label=""
                                                placeholder=""
                                                autocomplete="false"
                                                required
                                            ></multiselect>
                                            @error('category')
                                            <span class="text-danger">
                                                    {{$message}}
                                                </span>
                                            @enderror
                                        </div>
                                    @endif
                                </div>
                                @if($isGrocery)
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="control-label">
                                                @lang('strings.units') <b class="text-danger">*</b>
                                            </label>
                                            <multiselect
                                                :options="units"
                                                v-model="product.unit"
                                                track-by="id"
                                                label="title"
                                                name="unit"
                                                :searchable="true"
                                                :allow-empty="true"
                                                select-label=""
                                                selected-label=""
                                                deselect-label=""
                                                placeholder=""
                                                autocomplete="false"
                                                required
                                            ></multiselect>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-12">
                                    <span class="">
                                        <label class="switcher switcher-primary mr-3 my-2">
                                            <input type="checkbox" class="switcher-input"
                                                   v-model="isEnableToStoreDate"
                                                   name="is_enable_to_store_date"
                                                   :value="isEnableToStoreDate">
                                            <span class="switcher-indicator">
                                                <span class="switcher-yes">
                                                    <span class="ion ion-md-checkmark"></span>
                                                </span>
                                                <span class="switcher-no">
                                                    <span class="ion ion-md-close"></span>
                                                </span>
                                            </span>
                                        </label>
                                        Is enable to store date ?
                                    </span>
                                </div>
                                <div class="col-3">
                                    {{-- @component('admin.components.form-group', ['name' =>'price_discount_began_at', 'type' => 'date'])
                                         @slot('label', trans('strings.discount_begins_at'))
                                         @slot('value', \Carbon\Carbon::parse($product->price_discount_began_at))
                                         @slot('attributes',[
                                             'min' => now()->format('Y-m-d'),
                                         ])
                                     @endcomponent--}}
                                    <div class="form-group">
                                        <label
                                            for="price-discount-began-at">{{trans('strings.discount_begins_at')}}</label>
                                        <input type="date"
                                               id="price-discount-began-at" class="form-control"
                                               name="price_discount_began_at"
                                               @if(!is_null($product->price_discount_began_at))
                                               value="{{\Carbon\Carbon::parse($product->price_discount_began_at)->format(config('defaults.date.short_format'))}}"
                                               @endif
                                               min="{{now()->format('Y-m-d')}}"
                                               :disabled="!isEnableToStoreDate">
                                        <small class="form-text text-danger">
                                            @error('price_discount_began_at')
                                            {{$message}}
                                            @enderror
                                        </small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    {{--    @component('admin.components.form-group', ['name' =>'price_discount_finished_at', 'type' => 'date'])
                                            @slot('label', trans('strings.discount_ends_at'))
                                            @slot('value', \Carbon\Carbon::parse($product->price_discount_finished_at))
                                            @slot('attributes',[
                                                'min' => now()->addDay()->format('Y-m-d'),
                                            ])
                                        @endcomponent--}}
                                    <div class="form-group">
                                        <label
                                            for="price-discount-finished-at">{{trans('strings.discount_ends_at')}}</label>
                                        <input type="date"
                                               id="price-discount-finished-at" class="form-control"
                                               name="price_discount_finished_at"
                                               @if(!is_null($product->price_discount_finished_at))
                                               value="{{\Carbon\Carbon::parse($product->price_discount_finished_at)->format(config('defaults.date.short_format'))}}"
                                               @endif
                                               min="{{now()->addDay()->format('Y-m-d')}}"
                                               :disabled="!isEnableToStoreDate">
                                        <small class="form-text text-danger">
                                            @error('price_discount_finished_at')
                                            {{$message}}
                                            @enderror
                                        </small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    {{--  @component('admin.components.form-group', ['name' =>'custom_banner_began_at', 'type' => 'date'])
                                          @slot('label', trans('strings.banner_begins_at'))
                                          @slot('value', \Carbon\Carbon::parse($product->custom_banner_began_at))
                                          @slot('attributes',[
                                              'min' => now()->format('Y-m-d'),
                                          ])
                                      @endcomponent--}}
                                    <div class="form-group">
                                        <label
                                            for="custom-banner-began-at">{{trans('strings.banner_begins_at')}}</label>
                                        <input type="date"
                                               id="custom-banner-began-at" class="form-control"
                                               name="custom_banner_began_at"
                                               @if(!is_null($product->custom_banner_began_at))
                                               value="{{\Carbon\Carbon::parse($product->custom_banner_began_at)->format(config('defaults.date.short_format'))}}"
                                               @endif
                                               min="{{now()->format('Y-m-d')}}"
                                               :disabled="!isEnableToStoreDate">
                                        <small class="form-text text-danger">
                                            @error('custom_banner_began_at')
                                            {{$message}}
                                            @enderror
                                        </small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    {{--@component('admin.components.form-group', ['name' =>'custom_banner_ended_at', 'type' => 'date'])
                                        @slot('label', trans('strings.banner_ends_at'))
                                        @slot('value', \Carbon\Carbon::parse($product->custom_banner_ended_at))
                                        @slot('attributes',[
                                            'min' => now()->addDay()->format('Y-m-d'),
                                        ])
                                    @endcomponent--}}
                                    <div class="form-group">
                                        <label
                                            for="custom-banner-began-at">{{trans('strings.banner_ends_at')}}</label>
                                        <input type="date"
                                               id="custom-banner-began-at" class="form-control"
                                               name="custom_banner_ended_at"
                                               @if(!is_null($product->custom_banner_began_at))
                                               value="{{\Carbon\Carbon::parse($product->custom_banner_ended_at)->format(config('defaults.date.short_format'))}}"
                                               @endif
                                               min="{{now()->addDay()->format('Y-m-d')}}"
                                               :disabled="!isEnableToStoreDate">
                                        <small class="form-text text-danger">
                                            @error('custom_banner_ended_at')
                                            {{$message}}
                                            @enderror
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    @component('admin.components.form-group', ['name' => 'price', 'type' => 'number'])
                                        @slot('label', trans('strings.price'))
                                        @slot('attributes', ['required', 'placeholder' => '5000'])
                                        @if(! is_null($product->id))
                                            @slot('value', $product->price)
                                        @endif
                                    @endcomponent
                                </div>
                                <div class="col-md-3">
                                    @component('admin.components.form-group', ['name' => 'price_discount_amount', 'type' => 'number'])
                                        @slot('label', trans('strings.discount_amount'))
                                        @slot('attributes', ['placeholder' => '5000'])
                                        @if(! is_null($product->id))
                                            @slot('value', $product->price_discount_amount)
                                        @endif
                                    @endcomponent
                                </div>
                                @if($product->is_grocery)
                                    <div class="col-md-3">
                                        @component('admin.components.form-group', ['name' => 'available_quantity', 'type' => 'number'])
                                            @slot('label', trans('strings.available_quantity'))
                                            @slot('attributes', ['placeholder' => '24'])
                                            @slot('value', $product->available_quantity)
                                        @endcomponent
                                    </div>
                                @else
                                    <input type="hidden" name="available_quantity" value="">
                                @endif
                                {{--<div class="col-md-3">
                                    @component('admin.components.form-group', ['name' => 'maximum_orderable_quantity', 'type' => 'number'])
                                        @slot('label', trans('strings.maximum_orderable_quantity'))
                                        @slot('attributes', ['placeholder' => '5'])
                                        @if(! is_null($product->id))
                                            @slot('value', $product->maximum_orderable_quantity)
                                        @endif
                                    @endcomponent
                                </div>--}}
                                <div class="col-md-12">
                                    <span class="">
                                        <label class="switcher switcher-primary mr-3 my-2">
                                            <input type="checkbox" class="switcher-input"
                                                   name="price_discount_by_percentage" {{$product->price_discount_by_percentage ? 'checked' : ''}}>
                                            <span class="switcher-indicator">
                                                <span class="switcher-yes">
                                                    <span class="ion ion-md-checkmark"></span>
                                                </span>
                                                <span class="switcher-no">
                                                    <span class="ion ion-md-close"></span>
                                                </span>
                                            </span>
                                        </label>
                                        @lang('strings.price_discount_by_percentage')
                                    </span>
                                </div>
                                @if($product->is_grocery)
                                    <div class="col-md-12">
                                    <span class="">
                                    <label class="switcher switcher-primary mr-3 my-2">
                                        <input type="checkbox" class="switcher-input"
                                               name="is_storage_tracking_enabled" {{$product->is_storage_tracking_enabled ? 'checked' : ''}}>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes">
                                                <span class="ion ion-md-checkmark"></span>
                                            </span>
                                            <span class="switcher-no">
                                                <span class="ion ion-md-close"></span>
                                            </span>
                                        </span>
                                    </label>
                                        Enable Storage Tracking
                                    </span>
                                    </div>
                                @else
                                    <input type="hidden" name="is_storage_tracking_enabled" value="false">
                                @endif
                                <div class="col-6">
                                    <label class="control-label">
                                        @lang('strings.status') <b class="text-danger">*</b>
                                    </label>
                                    <multiselect
                                        :options="statuses"
                                        v-model="selectedStatus"
                                        label="title"
                                        select-label=""
                                        selected-label=""
                                        deselect-label=""
                                        placeholder="{{trans('strings.select_status')}}"
                                        autocomplete="false"
                                        required
                                    ></multiselect>
                                </div>
                                <div class="col-6">
                                    <label class="control-label">
                                        @lang('strings.search_tags')
                                    </label>
                                    <multiselect
                                        :options="searchTags"
                                        v-model="product.search_tags"
                                        track-by="id"
                                        label="title"
                                        :searchable="true"
                                        :multiple="true"
                                        :allow-empty="true"
                                        select-label=""
                                        selected-label=""
                                        deselect-label=""
                                        placeholder=""
                                        @input="selectSearchTags"
                                        autocomplete="false"
                                        required
                                    ></multiselect>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-outline-inverse">
                        <h4 class="card-header">Photos</h4>
                        <div class="card-body">
                            <div class="row">
                                @if(!$isGrocery)
                                    <div class="col-6">
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
                                @endif
                                @if($isGrocery)
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
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            @if(request()->has('branch_id'))
                <input type="hidden" name="chain_id" value="{{request()->chain_id}}">
                <input type="hidden" name="branch_id" value="{{request()->branch_id}}">
            @else
                <input type="hidden" name="chain" :value="JSON.stringify(product.chain)">
                <input type="hidden" name="branch" :value="JSON.stringify(product.branch)">
            @endif
            <input type="hidden" name="status" :value="selectedStatus.id">
            <input type="hidden" name="search_tags">
            <input type="hidden" name="categories" :value="JSON.stringify(product.categories)">
            <input type="hidden" name="category" :value="JSON.stringify(product.category)">
            <input type="hidden" name="unit_id" :value="JSON.stringify(product.unit)">
            <input type="hidden" name="unattached-media" class="deleted-file" value="">
            <button class="btn btn-success" type="submit">{{trans('strings.submit')}}</button>
            @if($product->id && $product->is_food)
                <a class="btn btn-outline-primary" type="submit" href="{{route('admin.products.options',$product)}}"
                   target="_blank">
                    Options
                </a>
            @endif
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
                branches: @json($branches),
                categories: @json($categories??[]),
                units: @json($units),
                searchTags: @json($searchTags),
                statuses: @json(array_values(\App\Models\Product::getAllStatusesRich())),
                isGrocery: @json($isGrocery),
                isEnableToStoreDate: false,
                selectedStatus: @json($selectedStatus),
            },
            beforeMount() {
            },
            mounted() {
                this.selectSearchTags(this.product.search_tags ?? []);
            },
            methods: {
                selectSearchTags: function (searchTags) {
                    if (!searchTags && searchTags.length > 0) {
                        const searchTagsIds = searchTags.map(item => item.id);
                        $(`input[name='search_tags']`).val(JSON.stringify(searchTagsIds));
                    }
                },
                selectChain: function () {
                    this.branches = [];
                    this.product.branch = null;
                },
                selectBranch: function () {
                    this.categories = [];
                    this.product.category = null;
                },
                getBranches: function () {
                    const branches = !!this.branches ? JSON.parse(JSON.stringify(this.branches)) : null;
                    let hasError = true;
                    let url = true ? @json(localization()->localizeURL(route('ajax.branch-by-chain', ['chain_id' => 'XMLSD']))) : '';
                    if (!!this.product.chain && !!this.product.chain.id) {
                        const chain_id = this.product.chain.id;
                        url = url.replaceAll('XMLSD', chain_id);
                        axios.get(url).then((res) => {
                            this.branches = res.data.branches;
                            this.selectBranch();

                            hasError = false;
                        }).catch(console.error).finally(() => {
                            if (hasError) {
                                this.branches = branches;
                            }
                        });
                    }
                },
                getMenuCategories: function () {
                    const categories = !!this.categories ? JSON.parse(JSON.stringify(this.categories)) : null;
                    let hasError = true;
                    let url = true ? @json(localization()->localizeURL(route('ajax.category-by-branch', ['branch_id' => 'XMLKE']))) : '';
                    if (!!this.product.branch && !!this.product.branch.id) {
                        const branch_id = this.product.branch.id;


                        url = url.replaceAll('XMLKE', branch_id);
                        axios.get(url).then((res) => {
                            this.categories = res.data.categories;
                            if (this.categories.length > 0) {
                                this.product.category = this.categories[0];
                            }
                            hasError = false;
                        }).catch(console.error).finally(() => {
                            if (hasError) {
                                this.categories = categories;
                            }
                        });
                    }
                },
            },
        })
    </script>
@endpush
