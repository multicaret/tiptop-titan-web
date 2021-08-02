<div class="nav-tabs-top nav-responsive-xl">
    <ul class="nav nav-tabs nav-justified">
        <li class="nav-item">
            <a class="nav-link active" href="">
                <i class="fas fa-edit"></i>&nbsp;TipTop Orders
            </a>
        </li>
        <li class="nav-item   ">
            <a class="nav-link " href="{{route('admin.jet.orders.index')}}">
                <i class="far fa-clock"></i>&nbsp;Jet Orders


                @if($jetOrdersCount > 0)
                    <div class="pl-1 ml-auto bounce d-inline-block" style="font-size:15px; ">

                        <div class="badge badge-danger d-inline-block">
                            {{$jetOrdersCount}}
                        </div>
                    </div>
                @endif
            </a>
        </li>
    </ul>
    <div class="tab-content mt-3">
        <div class="tab-pane fade  active show" id="settings-tab">
            <div class="row mb-3">
                <div class="col-3">
                    <div class="card">
                        <div class="card-body shadow px-4 py-3 rounded-lg">
                            <h4 class="card-title text-secondary">
                                New
                                {{--<span wire:loading wire:target="foodNewOrdersCount">
                                      <i class="fas fa-sync fa-spin"></i>
                                </span>--}}
                            </h4>
                            <div class="card-text">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-concierge-bell fa-2x text-primary"></i>
                                    <div class="media-body ml-4 text-secondary align-self-center">
                                        <h3 class="m-0" {{--wire:poll.1m--}}>
                                            {{$newOrders}}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="card">
                        <div class="card-body shadow px-4 py-3 rounded-lg">
                            <h4 class="card-title text-secondary">
                                Preparing
                                {{--<span wire:loading wire:target="foodNewOrdersCount">
                                      <i class="fas fa-sync fa-spin"></i>
                                </span>--}}
                            </h4>
                            <div class="card-text">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-concierge-bell fa-2x text-primary"></i>
                                    <div class="media-body ml-4 text-secondary align-self-center">
                                        <h3 class="m-0" {{--wire:poll.1m--}}>
                                            {{$preparingOrders}}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-2">
                    <div class="card">
                        <div class="card-body shadow px-4 py-3 rounded-lg">
                            <h4 class="card-title text-secondary">
                                Canceled
                                {{--<span wire:loading wire:target="groceryNewOrdersCount">
                                      <i class="fas fa-sync fa-spin"></i>
                                </span>--}}
                            </h4>
                            <div class="card-text">
                                <div class="media align-items-center">
                                    {{--<img src="/images/icons/food-delivery-186/svg/021-food delivery.svg"
                                         alt="Grocery Orders" class="d-block ui-w-50">--}}
                                    <i class="fas fa-times fa-2x text-primary"></i>
                                    <div class="media-body ml-4 text-secondary">
                                        <h3 class="m-0" {{--wire:poll.1m--}}>
                                            {{$canceledOrders}}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-2">
                    <div class="card">
                        <div class="card-body shadow px-4 py-3 rounded-lg">
                            <h4 class="card-title text-secondary">
                                Waiting Courier
                            </h4>
                            <div class="card-text">
                                <div class="media align-items-center">
                                    {{--<img src="/images/icons/svg/clock.svg" alt="Clock"
                                         class="d-block ui-w-50">--}}
                                    <i class="far fa-clock fa-2x text-primary"></i>
                                    <div class="media-body ml-4 text-secondary">
                                        <h3 class="m-0"> {{$waitingForCourierOrders}} </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="card">
                        <div class="card-body shadow px-4 py-3 rounded-lg">
                            <h4 class="card-title text-secondary">
                                On The Way
                            </h4>
                            <div class="card-text">
                                <div class="media align-items-center">
                                    {{--<img src="/images/icons/svg/clock.svg" alt="Clock"
                                         class="d-block ui-w-50">--}}
                                    <i class="fa fa-road fa-2x text-primary"></i>
                                    <div class="media-body ml-4 text-secondary">
                                        <h3 class="m-0"> {{$onTheWayOrders}} </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3"></div>
            <livewire:orders.orders-table/>
        </div>
        <div class="tab-pane fade" id="working-hours-tab">
            <div class="card-body">

            </div>
        </div>

    </div>
</div>
@push('styles')
    <style>
        .nav-link.active {
            box-shadow: 0 1rem 3rem rgb(24 28 33 / 18%) !important
        }

    </style>
@endpush
