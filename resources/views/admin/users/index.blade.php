@php
    request()->merge(['role'=>$role])
@endphp

@extends('layouts.admin')

@section('title')
    {{$title = ucwords(str_replace('-', ' ', Str::title($role)))}}
@endsection

@section('content')
    <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
        <div>{{ $title }}</div>
        <a href="{{ route('admin.users.create', ['role' => $role ]) }}">
            <button type="button" class="btn btn-primary d-block rounded-pill">
                <span class="ion ion-md-add"></span>&nbsp;
                @lang('strings.add')
            </button>
        </a>
    </h4>

    <div class="card">
        <div class="card-datatable table-responsive">
            @component('admin.components.datatables.index-without-ordering-ability')
                @slot('columns' , $columns)
                @slot('ajax_route' , route('ajax.datatables.users', request()->all()))
            @endcomponent
        </div>
    </div>
@endsection
