<div>
    <div class="mb-4">
        <div class="row mb-5">
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            New Orders
                            <span wire:loading wire:target="newOrdersCount">
                                  <i class="fas fa-sync fa-spin"></i>
                            </span>
                        </h4>
                        <div class="card-text">
                            <div class="media align-items-center">
                                <img src="/images/icons/food-delivery-17/svg/021-paper-bag.svg" alt="Orders"
                                     class="d-block ui-w-50">
                                <div class="media-body ml-4">
                                    <h3 wire:poll.1s>
                                    {{$newOrdersCount}}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            Current Time
                        </h4>
                        <div class="card-text">
                            <div class="media align-items-center">
                                <img src="/images/icons/food-delivery-17/svg/015-24-hours.svg" alt="Clock"
                                     class="d-block ui-w-50">
                                <div class="media-body ml-4">
                                    <span wire:poll.1m>{{ now() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ $auth->name }}
                        </h4>
                        <div class="card-text">
                            <div class="media align-items-center">
                                <img src="{{ $auth->avatar }}" alt="{{ $auth->name }}"
                                     class="d-block ui-w-50 rounded-circle">
                                <div class="media-body ml-4">
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
