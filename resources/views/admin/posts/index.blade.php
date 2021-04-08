@extends('layouts.admin')

@section('title')
    @lang('strings.'. Illuminate\Support\Str::plural($typeName,2))
@endsection

@section('content')

    <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
        @lang('strings.'. Illuminate\Support\Str::plural($typeName,2))
        <x-admin.add-copy-buttons
            :createRoute="route('admin.posts.create', ['type' => request()->type ])">
        </x-admin.add-copy-buttons>
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
