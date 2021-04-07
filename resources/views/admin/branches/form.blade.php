@extends('layouts.admin')

@if(!is_null($branch->id))
    @section('title', trans('strings.editing') .' - ' . trans('strings.branch'))
@else
    @section('title', trans('strings.add_new') .' - ' . trans('strings.branch'))
@endif

@push('styles')
    <link rel="stylesheet" href="/admin-assets/libs/quill/typography.css">
    <link rel="stylesheet" href="/admin-assets/libs/quill/editor.css">
    @livewireStyles
@endpush

@section('content')
    <div class="mb-4">
        @if(!is_null($branch->id))
            <h5>Editing Branch - {{ $branch->title }}</h5>
        @else
            <h5>{{trans('strings.add_new')}} Branch</h5>
        @endif
    </div>



    <div class="nav-tabs-top nav-responsive-xl">
        <ul class="nav nav-tabs nav-justified">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#navs-bottom-responsive-link-1">
                    <i class="fas fa-shopping-basket"></i>&nbsp;Settings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#navs-bottom-responsive-link-2">
                    <i class="fas fa-user"></i>&nbsp;Working Hours
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#products-tab">
                    <i class="fas fa-code-branch"></i>&nbsp;Products (meals)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#navs-bottom-responsive-link-4">
                    <i class="fas fa-code-branch"></i>&nbsp;Categories
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#navs-bottom-responsive-link-5">
                    <i class="fas fa-user-tie"></i>&nbsp;Managers & Drivers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" target="_blank" href="{{route('admin.orders.index',['branch-id' => $branch->id])}}">
                    <i class="fas fa-code-branch"></i>&nbsp;Orders
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" target="_blank"
                   href="{{route('admin.orders.ratings',['branch-id' => $branch->id])}}">
                    <i class="fas fa-code-branch"></i>&nbsp;Ratings
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active show" id="navs-bottom-responsive-link-1">
                <div class="card-body">
                    @include('admin.branches.partials._form-inner')
                </div>
            </div>
            <div class="tab-pane fade" id="navs-bottom-responsive-link-2">
                <div class="card-body">
                    @include('admin.branches.partials._working-hours')
                </div>
            </div>

            <div class="tab-pane fade" id="products-tab">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-3">
                        <a class="btn btn-primary" target="_blank"
                           href="{{route('admin.products.create',
                            ['type'=> request()->type,
                            'branch_id' => $branch->id,
                            'chain_id' => optional($branch->chain)->id])}}">
                            Add new product
                        </a>
                    </div>
                    <livewire:products-index :branch-id="$branch->id"/>
                </div>
            </div>

            <div class="tab-pane fade" id="navs-bottom-responsive-link-4">
                <div class="card-body">
                    <p>Tab content</p>
                </div>
            </div>

            <div class="tab-pane fade" id="navs-bottom-responsive-link-5">
                <div class="card-body">
                    <p>Tab content</p>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('/admin-assets/libs/quill/quill.js') }}"></script>
    {{--    <script src="/js/charts_gmaps.js"></script>--}}
    @include('admin.branches.partials._branch-js')


    @livewireScripts
    <script>
        Livewire.on('productStored', (params) => {
            if (params.timeout) {
                setTimeout(function () {
                    showToast(params.icon, params.message);
                }, params.timeout)
            } else {
                showToast(params.icon, params.message);
            }

        });
    </script>
@endpush
