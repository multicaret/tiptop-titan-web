@extends('layouts.admin')
@section('title', trans('strings.slides'))
@section('content')
    <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
        {{trans('strings.slides')}}
        <a href="{{ route('admin.slides.create') }}">
            <button type="button" class="btn btn-primary rounded-pill d-block">
                <span class="ion ion-md-add"></span>
                &nbsp;
                {{trans('strings.add')}}
            </button>
        </a>
    </h4>

    <div class="card">
        <div class="card-datatable table-responsive">
            @component('admin.components.datatables.index')
                @slot('columns', $columns)
                @slot('ajax_route', route('ajax.datatables.slides', request()->all()))
            @endcomponent
        </div>
    </div>
@endsection
