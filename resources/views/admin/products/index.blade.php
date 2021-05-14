@extends('layouts.admin')
@section('title', 'Products')
@section('content')
    <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
        @if(\App\Models\Product::isGrocery())
            Market Products
        @else
            Food Products
        @endif
        <x-admin.add-copy-buttons
            :createRoute="route('admin.products.create',['type'=> request()->type])">
        </x-admin.add-copy-buttons>
    </h4>

    <div class="card">
        <div class="card-datatable table-responsive">
            @component('admin.components.datatables.index-without-ordering-ability')
                @slot('columns', $columns)
                @slot('ajax_route', route('ajax.datatables.products', request()->all()))
            @endcomponent
        </div>
    </div>


@endsection
