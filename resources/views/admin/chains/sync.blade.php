@extends('layouts.admin')
@section('title', 'Chains')
@section('content')
    <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
        Sync to Branches
    </h4>

    <form action="{{route('admin.chains.sync',$chain)}}" method="post">
        @csrf
        <div class="card card-outline-inverse">
            <h4 class="card-header">Product Sync</h4>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h5>Branches</h5>
                        @component('admin.components.form-group', ['name' => 'sync_branch_ids[]', 'type' => 'select'])
                            @slot('options',$chain->branches->pluck('title','id'))
                            @slot('attributes', [
                                'multiple',
                                'data-select-two' => 'tags-only',
                            ])
                        @endcomponent
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-success" type="submit">{{trans('strings.submit')}}</button>
                </div>
            </div>
        </div>
    </form>


@endsection
