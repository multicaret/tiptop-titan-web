@extends('layouts.admin')
@section('title')
    @lang('strings.'. Illuminate\Support\Str::plural($typeName,2))
@endsection
@section('content')

    <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
        <div class="d-block">
            @lang('strings.'. Illuminate\Support\Str::plural($typeName,2))
            @if(\App\Models\Taxonomy::getCorrectType(request('type')) == \App\Models\Taxonomy::TYPE_GROCERY_CATEGORY)
                <div class="m-3 btn-group">
                    <button type="button" class="btn btn-secondary dropdown-toggle"
                            data-toggle="dropdown">{{is_null(request('parent_id')) ? "All Groups": \App\Models\Taxonomy::find(request('parent_id'))->title}}</button>
                    <div class="dropdown-menu" style="z-index: 9999999;">
                        @if(!is_null(request('parent_id')))
                            <a class="dropdown-item"
                               href="{{route("admin.taxonomies.index", [
                                            "type" => request('type'),
                                            ])}}">All Groups</a>
                            <div class="dropdown-divider"></div>
                        @endif
                        @foreach(\App\Models\Taxonomy::parents()
                                                        ->whereType(\App\Models\Taxonomy::getCorrectType(request('type')))
                                                        ->get()
                                                         ->filter(function ($category){
                                                            return $category->hasChildren();
                                                        })
                                                        as $category)
                            @if(request('parent_id') != $category->id)
                                <a class="dropdown-item"
                                   href=" {{route("admin.taxonomies.index", ["type" => request('type'), 'parent_id' => $category->id])}}">{{$category->title}}</a>
                                @if(!$loop->last)
                                    <div class="dropdown-divider"></div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
        <a href="{{ route('admin.taxonomies.create', ['type' => request()->type ]) }}">
            <button type="button" class="btn btn-primary d-block rounded-pill">
                <span class="ion ion-md-add"></span>&nbsp;
                @lang('strings.add')
            </button>
        </a>
    </h4>

    <div class="card">
        <div class="card-datatable table-responsive">
            @component('admin.components.datatables.index')
                @slot('columns', $columns)
                @slot('ajax_route', route('ajax.datatables.taxonomies', request()->all()))
                @slot('route_params',request()->all())
            @endcomponent
        </div>
    </div>


@endsection
