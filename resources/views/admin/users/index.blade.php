@extends('layouts.admin')

@section('title')
    {{ ucfirst(Illuminate\Support\Str::plural(request()->type,2)) }}
@endsection

@section('content')
    <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
        <div>{{ ucfirst(Illuminate\Support\Str::plural(request()->type,2)) }}</div>
        <a href="{{ route('admin.users.create', ['type' => request()->type ]) }}">
            <button type="button" class="btn btn-primary d-block">
                <span class="ion ion-md-add"></span>&nbsp;
                @lang('strings.add')
            </button>
        </a>
    </h4>

    <div class="card">
        <div class="card-datatable table-responsive">
            @component('admin.components.datatables.index')
                @slot('columns' , [
                [
                    'data' => 'first' ,
                    'name' => 'first' ,
                    'title' => trans('strings.first_name') ,
                ],
                [
                    'data' => 'last' ,
                    'name' => 'last' ,
                    'title' => trans('strings.last_name') ,
                ],
                [
                    'data' => 'username' ,
                    'name' => 'username' ,
                    'title' => trans('strings.username') ,
                ],
                [
                    'data' => 'email' ,
                    'name' => 'email' ,
                    'title' => trans('strings.email') ,
                ],
                [
                    'data' => 'created_at' ,
                    'name' => 'created_at' ,
                    'title' => trans('strings.create_date')
                ],
                [
                    'data' => 'last_logged_in_at' ,
                    'name' => 'last_logged_in_at' ,
                    'title' => __('Last logged In')
                ],
                ])
                @slot('ajax_route' , route('ajax.datatables.users', request()->all()))
            @endcomponent
        </div>
    </div>
@endsection
