@extends('layouts.admin')
@section('title', 'Products')
@section('content')
    <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
        @if(\App\Models\Product::isGrocery())
            Market Products
        @else
            Food Products
        @endif
        @if(!\App\Models\Product::isGrocery())
            <x-admin.add-copy-buttons
                :createRoute="route('admin.products.create',[
            'type'=> request()->type,
            'only-for-chains' => request()->has('only-for-chains') && request()->input('only-for-chains'),
            ])">
            </x-admin.add-copy-buttons>
        @endif
    </h4>

    <div class="card">
        <div class="card-datatable table-responsive">
            @component('admin.components.datatables.index-without-ordering-ability')
                @slot('columns', $columns)
                @slot('ajax_route', route('ajax.datatables.products'))
                @slot('route_params', request()->all())
            @endcomponent
        </div>
    </div>


@endsection
