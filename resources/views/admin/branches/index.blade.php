@extends('layouts.admin')
@section('title', 'Branches')
@section('content')
    <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
        @if(\App\Models\Branch::isGrocery())
            Market Branches
        @else
            Food Branches
        @endif
        @if(request()->has('type'))
            <div>
                @if(\App\Models\Branch::isFood())
                    <a href="{{ route('admin.restaurants.create') }}">
                        <button type="button" class="btn btn-secondary rounded-pill">
                            <span class="ion ion-md-add"></span>
                            &nbsp;
                            {{trans('strings.add_restaurant')}}
                        </button>
                    </a>
                @endif
                <a href="{{ route('admin.branches.create',['type'=> request()->type]) }}">
                    <button type="button" class="btn btn-primary rounded-pill">
                        <span class="ion ion-md-add"></span>
                        &nbsp;
                        {{trans('strings.add_branch')}}
                    </button>
                </a>
            </div>
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
