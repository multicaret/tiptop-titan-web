@extends('layouts.admin')
@if(!is_null($taxonomy->id))
    @section('title','Editing')
@else
    @section('title','Add New')
@endif

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.taxonomies.index', ['type' => request()->type]) }}">
            {{ trans('strings.'. Illuminate\Support\Str::plural($typeName,2)) }}
        </a>
    </li>
    <li class="breadcrumb-item active">
        @if(!is_null($taxonomy->id))
            @lang('strings.editing') {{ trans('strings.'. $typeName) }} ({{ $taxonomy->title }})
        @else
            @lang('strings.add_new_'. $typeName)
        @endif
    </li>
@endsection


@section('content')
    @if(is_null($taxonomy->id))
        {!! Form::open([
        'route' => ['admin.taxonomies.store', 'type' => request('type')],
         'files' => true
         ]) !!}
    @else
        {!! Form::open([
            'route' => ['admin.taxonomies.update', $taxonomy,'type' => request('type')],
            'method' => 'PATCH',
            'files' => true,
        ]) !!}
    @endif
    <div class="row mb-4">
        <div class="col-12">
        {{--<ul class="nav nav-tabs border-bottom-0">
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
        </ul>--}}<!-- /.nav-tabs -->

        {{--<div class="tab-content tabcontent-border card card-outline-inverse">
            @foreach(localization()->getSupportedLocales() as $langKey => $locale)
                <div class="tab-pane {{ $langKey == localization()->getDefaultLocale() ? 'active' : '' }}"
                     id="title_{{$langKey}}">

                    <div class="card-body">
                    </div>
                </div>
            @endforeach
        </div>--}}<!-- /.tab-content -->


            <div class="row">
                @foreach(localization()->getSupportedLocales() as $langKey => $locale)
                    <div class="col-md-4 mt-4">
                        <div class="card card-outline-inverse">
                            <h4 class="card-header">{{Str::upper($langKey)}}</h4>
                            <div class="card-body">
                                <div class="row p-t-20">
                                    <div class="col-md-12">
                                        @component('admin.components.form-group', ['name' => $langKey .'[title]', 'type' => 'text'])
                                            @slot('label', 'Title')
                                            @if($langKey === localization()->getCurrentLocale())
                                                @slot('attributes', ['required'])
                                            @endif
                                            @if(!is_null($taxonomy->id))
                                                @slot('value', optional($taxonomy->translate($langKey))->title)
                                            @endif
                                        @endcomponent
                                    </div>
                                    @if(in_array($correctType, \App\Models\Taxonomy::typesHaving('content')))
                                        <div class="col-md-12">
                                            @component('admin.components.form-group', ['name' => $langKey .'[description]', 'type' => 'textarea'])
                                                @slot('label', 'Description')
                                                @slot('attributes', ['rows'=>2])
                                                @if(!is_null($taxonomy->id))
                                                    @slot('value', optional($taxonomy->translate($langKey))->description)
                                                @endif
                                            @endcomponent
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card card-outline-inverse mb-4">
        <h4 class="card-header">Details</h4>
        <div class="card-body">
            <div class="row">
                @if(in_array($correctType, \App\Models\Taxonomy::typesHaving('parent')) && (!$taxonomy->id || !$taxonomy->hasChildren())) {{--This makes sure taxonomies with children do not have a parent input--}}
                    <div class="col-md-4">
                        @component('admin.components.form-group', ['name' => 'parent_id', 'type' => 'select'])
                            @slot('label', 'Parent')
                            @slot('options', $roots->push([
                                'id' => null,
                                'title' => '-- Parent (ROOT) --',
                            ])->pluck('title', 'id'))
                            @slot('selected' , $taxonomy->parent_id ?? '')
                        @endcomponent
                    </div>
                @endif
                @if($correctType == \App\Models\Taxonomy::TYPE_INGREDIENT)
                    <div class="col-6">
                        @component('admin.components.form-group', ['name' => 'ingredient_category_id', 'type' => 'select'])
                            @slot('label', 'Ingredient Category')
                            @slot('options', $ingredientCategories->prepend(null)->pluck('title','id'))
                            @slot('attributes', ['required','class'=>'select-2-ingredient-category w-100'])
                            @slot('selected', $taxonomy->ingredient_category_id)
                        @endcomponent
                    </div>
                @endif
                @if(in_array($correctType, [\App\Models\Taxonomy::TYPE_MENU_CATEGORY]))
                    <div class="col-4">
                        @component('admin.components.form-group', ['name' => 'branch_id', 'type' => 'select'])
                            @slot('label', trans('strings.branch'))
                            @slot('options', $branches)
                            @slot('attributes', [
                                'class' => 'select2-branch w-100',
                                'required',
                                $menuCategoryData['hasBranch'] ? 'disabled' : '',
                            ])
                            @slot('selected', $menuCategoryData['hasBranch'] ?  $menuCategoryData['branchId'] : $taxonomy->branch_id)
                        @endcomponent
                    </div>
                @endif
                <div
                    class="{{in_array($correctType, [\App\Models\Taxonomy::TYPE_INGREDIENT,\App\Models\Taxonomy::TYPE_INGREDIENT_CATEGORY]) ? 'col-6':'col-4'}}">
                    @component('admin.components.form-group', ['name' => 'status', 'type' => 'select'])
                        @slot('label', trans('strings.status'))
                        @slot('attributes', ['class'=>'select-2-status w-100'])
                        @slot('options', \App\Models\Taxonomy::getStatusesArray())
                        @slot('selected', $taxonomy->status)
                    @endcomponent
                </div>

                @if($correctType == \App\Models\Taxonomy::TYPE_FOOD_CATEGORY)
                    <div
                        class="col-4">
                        @component('admin.components.form-group', ['name' => 'search_tags[]', 'type' => 'select'])
                            @slot('label', trans('strings.search_tags'))
                            @slot('attributes', ['class'=>'select-2-search-tags w-100', 'multiple'])
                            @slot('options', $searchableTags->pluck('title','id'))
                            @slot('selected', $taxonomy->searchableTags)
                        @endcomponent
                    </div>
                @endif
                @if($correctType == \App\Models\Taxonomy::TYPE_UNIT)
                    <div class="col-md-12">
                        @component('admin.components.form-group', ['name' => 'step', 'type' => 'number'])
                            @slot('label', 'Step')
                            @slot('attributes', [
                                'step' => 'any',
                                'min'=>'0'
                            ])
                            @slot('value', $taxonomy->step)
                        @endcomponent
                    </div>
                @endif
                @if(in_array($correctType, [\App\Models\Taxonomy::TYPE_GROCERY_CATEGORY]))
                    <div class="col-4">
                        @component('admin.components.form-group', ['name' => 'chain_id', 'type' => 'select'])
                            @slot('label', trans('strings.chain'))
                            @slot('options',  \App\Models\Chain::whereType(\App\Models\Chain::CHANNEL_GROCERY_OBJECT)->get()->pluck('title', 'id')->prepend('',''))
                            @slot('attributes', [
                                'class' => 'select2-chain w-100',
                                'required',
                            ])
                            @slot('selected', $taxonomy->chain_id)
                        @endcomponent
                    </div>
                @endif
                {{--@if($correctType == \App\Models\Taxonomy::TYPE_FOOD_CATEGORY)
                    --}}{{-- Branches --}}{{--
                    <div class="col-4">
                        @component('admin.components.form-group', ['name' => 'branches[]', 'type' => 'select'])
                            @slot('label', trans('strings.branches'))
                            @slot('options', $branches->pluck('title', 'id'))
                            @slot('attributes', [
                                'multiple',
                                'required',
                                'class' => 'select2-branches w-100',
                            ])
                            @slot('selected', $taxonomy->branches)
                        @endcomponent
                    </div>
                @endif--}}
                @if(in_array($correctType, \App\Models\Taxonomy::typesHaving('cover_image')))
                    <div class="col-md-4">
                        @component('admin.components.form-group', ['name' => 'cover', 'type' => 'file'])
                            @slot('label', 'Cover')
                            @slot('attributes', [
                                'class' => 'cover-uploader',
                                'accept' => '.jpg, .jpeg, .png, .bmp',
                                'dropzone' => 'media-list',
                                'data-fileuploader-listInput' => 'media-list',
                                'data-fileuploader-extensions' => 'jpg, jpeg, png, bmp',
                                'data-fileuploader-files' => json_encode($taxonomy->getMediaForUploader('cover'), JSON_UNESCAPED_UNICODE),
                            ])
                        @endcomponent
                    </div>
                @endif
            </div>
        </div>
    </div>

    <button class="btn btn-success" type="submit">@lang('strings.submit')</button>
    {!! Form::close() !!}

@endsection

@push('scripts')
    <script src="{{ asset('/admin-assets/libs/quill/quill.js') }}"></script>
    <script src="/admin-assets/libs/select2/select2.js"></script>
    <script>
        $(function () {
            $('.select2-branches').select2({
                placeholder: 'Select Branches',
            });
        });
        $(function () {
            $('.select2-branch').select2({
                placeholder: 'Select Branch',
            });
            $('.select-2-ingredient-category').select2({
                placeholder: 'Select Ingredient Category',
            });
            $('.select-2-status').select2({
                placeholder: 'Select Status',
            });
            $('.select2-chain').select2({
                placeholder: 'Select Chain',
            });
            $('.select-2-search-tags').select2({
                placeholder: 'Select search tags',
            });
        });
    </script>
@endpush


