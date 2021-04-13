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
          action="{{route('admin.products.store',['type' => strtolower($typeName)])}}"
          @else
          action="{{route('admin.products.update', ['type' => strtolower($typeName),$product->uuid])}}"
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
                                            @component('admin.components.form-group', ['name' => $langKey .'[notes]', 'type' => 'textarea'])
                                                @slot('label', 'Notes')
                                                @slot('attributes', [
                                                        'rows' => 2,
                                                        ])
                                                @if(! is_null($product->id))
                                                    @slot('value', optional($product->translate($langKey))->notes)
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
                                                {{--                                                @input="getCategories"--}}
                                                {{--                                                @select="selectChain"--}}
                                                placeholder=""
                                                autocomplete="false"
                                                required
                                            ></multiselect>
                                        </div>
                                    </div>
                                @endif
                                <div
                                    class="col-{{$isGrocery ? "6" : "12"}}">
                                    <div class="form-group">
                                        <label class="control-label">
                                            {{$isGrocery ? trans('strings.categories') : trans('strings.menu-category')}}
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
                                    </div>
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
                                <div class="col-3">
                                    @component('admin.components.form-group', ['name' =>'price_discount_began_at', 'type' => 'datetime-local'])
                                        @slot('label', trans('strings.discount_begins_at'))
                                        @slot('value', \Carbon\Carbon::parse($product->price_discount_began_at))
                                        @slot('attributes',[])
                                    @endcomponent
                                </div>
                                <div class="col-3">
                                    @component('admin.components.form-group', ['name' =>'price_discount_finished_at', 'type' => 'datetime-local'])
                                        @slot('label', trans('strings.discount_ends_at'))
                                        @slot('value', \Carbon\Carbon::parse($product->price_discount_finished_at))
                                        @slot('attributes',[])
                                    @endcomponent
                                </div>
                                <div class="col-3">
                                    @component('admin.components.form-group', ['name' =>'custom_banner_began_at', 'type' => 'datetime-local'])
                                        @slot('label', trans('strings.banner_begins_at'))
                                        @slot('value', \Carbon\Carbon::parse($product->custom_banner_began_at))
                                        @slot('attributes',[])
                                    @endcomponent
                                </div>
                                <div class="col-3">
                                    @component('admin.components.form-group', ['name' =>'custom_banner_ended_at', 'type' => 'datetime-local'])
                                        @slot('label', trans('strings.banner_ends_at'))
                                        @slot('value', \Carbon\Carbon::parse($product->custom_banner_ended_at))
                                        @slot('attributes',[])
                                    @endcomponent
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
                                        @slot('attributes', ['required', 'placeholder' => '5000'])
                                        @if(! is_null($product->id))
                                            @slot('value', $product->price_discount_amount)
                                        @endif
                                    @endcomponent
                                </div>
                                <div class="col-md-3">
                                    @component('admin.components.form-group', ['name' => 'available_quantity', 'type' => 'number'])
                                        @slot('label', trans('strings.available_quantity'))
                                        @slot('attributes', ['placeholder' => '24'])
                                        @if(! is_null($product->id))
                                            @slot('value', $product->available_quantity)
                                        @endif
                                    @endcomponent
                                </div>
                                <div class="col-md-3">
                                    @component('admin.components.form-group', ['name' => 'maximum_orderable_quantity', 'type' => 'number'])
                                        @slot('label', trans('strings.maximum_orderable_quantity'))
                                        @slot('attributes', ['placeholder' => '5'])
                                        @if(! is_null($product->id))
                                            @slot('value', $product->maximum_orderable_quantity)
                                        @endif
                                    @endcomponent
                                </div>
                                <div class="col-md-12">
                                    <span class="">
                                        <label class="switcher switcher-primary mr-3 my-2">
                                            <input type="checkbox" class="switcher-input"
                                                   name="discount_by_percentage" {{$product->discount_by_percentage ? 'checked' : ''}}>
                                            <span class="switcher-indicator">
                                                <span class="switcher-yes">
                                                    <span class="ion ion-md-checkmark"></span>
                                                </span>
                                                <span class="switcher-no">
                                                    <span class="ion ion-md-close"></span>
                                                </span>
                                            </span>
                                        </label>
                                        @lang('strings.discount_by_percentage')
                                    </span>
                                </div>
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
                                <div class="col-6">
                                    @component('admin.components.form-group', ['name' => 'status', 'type' => 'select'])
                                        @slot('label', trans('strings.status'))
                                        @slot('options', \App\Models\Product::getStatusesArray())
                                        @slot('attributes', ['required'])
                                        @slot('selected', $product->status)
                                    @endcomponent
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
            <input type="hidden" name="categories" :value="JSON.stringify(product.categories)">
            <input type="hidden" name="unit_id" :value="JSON.stringify(product.unit)">
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
                branches: @json($branches),
                categories: @json($categories),
                units: @json($units),
                isGrocery: @json($isGrocery),
            },
            beforeMount() {
            },
            methods: {
                selectChain: function () {
                    this.branches = [];
                    this.product.branch = null;
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
                            if (this.branches.length > 0) {
                                this.product.branch = this.branches[0];
                            }
                            hasError = false;
                        }).catch(console.error).finally(() => {
                            if (hasError) {
                                this.branches = branches;
                            }
                        });
                    }
                },
                getCategories: function () {
                    const branches = !!this.branches ? JSON.parse(JSON.stringify(this.branches)) : null;
                    let hasError = true;
                    let url = true ? @json(localization()->localizeURL(route('ajax.branch-by-chain', ['chain_id' => 'XMLSD']))) : '';
                    if (!!this.product.chain && !!this.product.chain.id) {
                        const chain_id = this.product.chain.id;


                        url = url.replaceAll('XMLSD', chain_id);
                        axios.get(url).then((res) => {
                            this.branches = res.data.branches;
                            if (this.branches.length > 0) {
                                this.product.branch = this.branches[0];
                            }
                            hasError = false;
                        }).catch(console.error).finally(() => {
                            if (hasError) {
                                this.branches = branches;
                            }
                        });
                    }
                },
            },
        })
    </script>
@endpush
