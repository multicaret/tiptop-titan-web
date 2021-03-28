@extends('layouts.admin')
@section('title', 'Branches')
@section('content')
    <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
        @if($typeName == 'grocery-branch')
            Market Branches
        @else
            Food Branches
        @endif
        @if(request()->has('type'))
            <a href="{{ route('admin.branches.create',['type'=> request()->type]) }}">
                <button type="button" class="btn btn-primary rounded-pill d-block">
                    <span class="ion ion-md-add"></span>
                    &nbsp;
                    {{trans('strings.add')}}
                </button>
            </a>
        @endif
    </h4>

    <div class="card">
        <div class="card-datatable table-responsive">
            @component('admin.components.datatables.index')
                @slot('columns', $columns)
                @slot('ajax_route', route('ajax.datatables.branches', request()->all()))
            @endcomponent
        </div>
    </div>


@endsection
