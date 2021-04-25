@extends('layouts.admin')
@section('title', trans('strings.payment_methods'))
@section('content')
    <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
        {{trans('strings.payment_methods')}}
    </h4>

    <div class="card">
        <div class="card-datatable table-responsive">
            @component('admin.components.datatables.index-without-ordering-ability')
                @slot('columns', $columns)
                @slot('ajax_route', route('ajax.datatables.payment-methods', request()->all()))
            @endcomponent
        </div>
    </div>
@endsection
