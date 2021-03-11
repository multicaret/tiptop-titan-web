@extends('layouts.admin')

@section('title')
    @lang('strings.'. Illuminate\Support\Str::plural($typeName,2))
@endsection

@section('content')

    <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
        @lang('strings.'. Illuminate\Support\Str::plural($typeName,2))
        <a href="{{ route('admin.posts.create', ['type' => request()->type ]) }}">
            <button type="button" class="btn btn-primary d-block">
                <span class="ion ion-md-add"></span>&nbsp;
                @lang('strings.add')
            </button>
        </a>
    </h4>

    <div class="card">
        <div class="card-datatable table-responsive">
            @component('admin.components.datatables.index')
                @slot('columns', $columns)
                @slot('ajax_route', route('ajax.datatables.posts', request()->all()))
            @endcomponent
        </div>
    </div>


@endsection
