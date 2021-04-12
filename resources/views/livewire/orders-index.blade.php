<div>
    <div class="mb-4">
        <div class="row mb-3">
            <div class="col-3">
                <div class="card">
                    <div class="card-body shadow px-4 py-3 rounded-lg">
                        <h4 class="card-title text-secondary">
                            Food New Orders
                            {{--<span wire:loading wire:target="foodNewOrdersCount">
                                  <i class="fas fa-sync fa-spin"></i>
                            </span>--}}
                        </h4>
                        <div class="card-text">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-concierge-bell fa-3x text-primary"></i>
                                <div class="media-body ml-4 text-secondary align-self-center">
                                    <h3 class="m-0" {{--wire:poll.1m--}}>
                                        {{$foodNewOrdersCount}}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-3">
                <div class="card">
                    <div class="card-body shadow px-4 py-3 rounded-lg">
                        <h4 class="card-title text-secondary">
                            Grocery New Orders
                            {{--<span wire:loading wire:target="groceryNewOrdersCount">
                                  <i class="fas fa-sync fa-spin"></i>
                            </span>--}}
                        </h4>
                        <div class="card-text">
                            <div class="media align-items-center">
                                {{--<img src="/images/icons/food-delivery-186/svg/021-food delivery.svg"
                                     alt="Grocery Orders" class="d-block ui-w-50">--}}
                                <i class="fas fa-shopping-basket fa-3x text-primary"></i>
                                <div class="media-body ml-4 text-secondary">
                                    <h3 class="m-0" {{--wire:poll.1m--}}>
                                        {{$groceryNewOrdersCount}}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-3">
                <div class="card">
                    <div class="card-body shadow px-4 py-3 rounded-lg">
                        <h4 class="card-title text-secondary">
                            Current Time
                        </h4>
                        <div class="card-text">
                            <div class="media align-items-center">
                                {{--<img src="/images/icons/svg/clock.svg" alt="Clock"
                                     class="d-block ui-w-50">--}}
                                <i class="far fa-clock fa-3x text-primary"></i>
                                <div class="media-body ml-4 text-secondary">
                                    <span>{{ now()->format(config('defaults.date.normal_format')) }}</span>
                                    &nbsp;&nbsp; - &nbsp;&nbsp;
                                    <span {{--wire:poll.1m--}}>{{ now()->format(config('defaults.time.normal_format')) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body shadow px-4 py-3 rounded-lg">
                        <h4 class="card-title text-secondary">
                            {{ $auth->name }}
                        </h4>
                        <div class="card-text">
                            <div class="media align-items-center">
                                <img src="{{ $auth->avatar }}" alt="{{ $auth->name }}"
                                     class="d-block ui-w-40 rounded-circle">
                                <div class="media-body ml-4 text-secondary">
                                    <div class="text-muted mb-2">{{ $auth->email }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <livewire:orders-table/>
    </div>
</div>
