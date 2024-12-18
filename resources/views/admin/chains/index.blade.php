@extends('layouts.admin')
@section('title', 'Chains')
@section('content')
    <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
        @if(\App\Models\Chain::isGrocery())
            Market Chains
        @else
            Food Chains
        @endif
        <div>
            @if(\App\Models\Chain::isFood())
                <a href="{{ route('admin.restaurants.create') }}">
                    <button type="button" class="btn btn-secondary rounded-pill">
                        <span class="ion ion-md-add"></span>
                        &nbsp;
                        {{trans('strings.add_restaurant')}}
                    </button>
                </a>
            @endif
            <a href="{{ route('admin.chains.create',['type'=> request()->type]) }}">
                <button type="button" class="btn btn-primary rounded-pill">
                    <span class="ion ion-md-add"></span>
                    &nbsp;
                    {{trans('strings.add_chain')}}
                </button>
            </a>
        </div>
    </h4>

    <div class="card">
        <div class="card-datatable table-responsive">
            @component('admin.components.datatables.index-without-ordering-ability')
                @slot('columns', $columns)
                @slot('ajax_route', route('ajax.datatables.chains', request()->all()))
            @endcomponent
        </div>
    </div>


@endsection
