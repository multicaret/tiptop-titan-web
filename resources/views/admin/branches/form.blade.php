@extends('layouts.admin')

@if(!is_null($branch->id))
    @section('title', trans('strings.editing') .' - ' . trans('strings.branch'))
@else
    @section('title', trans('strings.add_new') .' - ' . trans('strings.branch'))
@endif

@push('styles')
    <link rel="stylesheet" href="/admin-assets/libs/quill/typography.css">
    <link rel="stylesheet" href="/admin-assets/libs/quill/editor.css">
    {{--    @livewireStyles--}}
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
                    <i class="fas fa-edit"></i>&nbsp;Settings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#navs-bottom-responsive-link-2">
                    <i class="far fa-clock"></i>&nbsp;Working Hours
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#products-tab">
                    @if($branch->type == \App\Models\Branch::CHANNEL_GROCERY_OBJECT)
                        <i class="fas fa-carrot"></i>&nbsp;
                                                     Products
                    @else
                        <i class="fas fa-pizza-slice"></i>&nbsp;
                                                     Meals
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#navs-bottom-responsive-link-4">
                    <i class="fas fa-shapes"></i>&nbsp;Categories
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#navs-bottom-responsive-link-5">
                    <i class="fas fa-user-tie"></i>&nbsp;Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" target="_blank" href="{{route('admin.orders.index',['branch-id' => $branch->id])}}">
                    @if($branch->type == \App\Models\Branch::CHANNEL_GROCERY_OBJECT)
                        <i class="fas fa-shopping-basket"></i>&nbsp;Orders
                    @else
                        <i class="fas fa-concierge-bell"></i>&nbsp;Orders
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" target="_blank"
                   href="{{route('admin.orders.ratings',['branch-id' => $branch->id])}}">
                    <i class="fas fa-star"></i>&nbsp;Ratings
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
                    @if(is_null($branch->id))
                        @include('admin.branches.partials._inaccessible')
                    @else
                        <form method="post" enctype="multipart/form-data"
                              action="{{route('admin.branch.working-hours',[$branch->uuid])}}">
                            {{csrf_field()}}
                            @include('admin.branches.partials._working-hours')
                            <input type="hidden" name="workingHours" :value="JSON.stringify(workingHours)">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="tab-pane fade" id="products-tab">
                <div class="card-body">
                    @if(is_null($branch->id))
                        @include('admin.branches.partials._inaccessible')
                    @else
                        @php
                            $productChannels = \App\Models\Product::getChannelsArray();
                            $currentBranchChannel = \App\Models\Branch::getChannelsArray()[\App\Models\Branch::CHANNEL_GROCERY_OBJECT];
                        @endphp
                        @if($branch->id)
                            <div class="d-flex justify-content-end mb-3">
                                <a class="btn btn-primary" target="_blank"
                                   href="{{route('admin.products.create', [
                                        'type'=> request()->type ==  $currentBranchChannel?
                                            $productChannels[\App\Models\Product::CHANNEL_GROCERY_OBJECT] :
                                            $productChannels[\App\Models\Product::CHANNEL_FOOD_OBJECT],
                                        'branch_id' => $branch->id,
                                        'chain_id' => optional($branch->chain)->id
                                   ])}}">
                                    Add new product
                                </a>
                            </div>
                        @endif

                        <livewire:products.products-table :branch="$branch" :branch-id="$branch->id"/>
                    @endif
                </div>
            </div>
            <div class="tab-pane fade" id="navs-bottom-responsive-link-4">
                <div class="card-body">
                    @if(is_null($branch->id))
                        @include('admin.branches.partials._inaccessible')
                    @else
                        @if($branch->id)
                            <div class="d-flex justify-content-end mb-3">
                                <a class="btn btn-primary" target="_blank"
                                   href="{{route('admin.taxonomies.create', [
                                        'type'=> \App\Models\Taxonomy::getTypesArray()[\App\Models\Taxonomy::TYPE_MENU_CATEGORY],
                                        'branch_id' => $branch->id,
                                        'chain_id' => optional($branch->chain)->id
                                   ])}}">
                                    Add new category
                                </a>
                            </div>
                        @endif
                        <livewire:taxonomies.taxonomies-table {{--:branch="$branch"--}} :branch-id="$branch->id"/>
                    @endif
                </div>
            </div>

            <div class="tab-pane fade" id="navs-bottom-responsive-link-5">
                <div class="card-body">
                    @if(is_null($branch->id))
                        @include('admin.branches.partials._inaccessible')
                    @else
                        @foreach([
                                    \App\Models\User::ROLE_BRANCH_OWNER => [$branch->owners, 'truck'],
                                    \App\Models\User::ROLE_BRANCH_MANAGER => [$branch->managers, 'user-plus'],
                                    \App\Models\User::ROLE_RESTAURANT_DRIVER => [$branch->drivers, 'users-cog'],
                                ] as $role => $users)
                            @if($branch->id)
                                <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-2">
                                    <i class="fas fa-{{$users[1]}}" style="font-size:1.4em"> {{Str::plural(str_replace('-', ' ', Str::title($role)))}}</i>
                                    <x-admin.add-copy-buttons
                                        :createRoute="route('admin.users.create',[
                                        'role'=> $role,
                                        'branch_id' => $branch->id,
                                        'chain_id' => optional($branch->chain)->id
                                   ])">

                                    </x-admin.add-copy-buttons>
                                </h4>
                            @endif
                            @include('admin.branches.partials._users_table', ['users' => $users[0]])
                        @endforeach
                    @endif
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('/admin-assets/libs/quill/quill.js') }}"></script>
    {{--    <script src="/js/charts_gmaps.js"></script>--}}
    @include('admin.branches.partials._branch-js')
@endpush
