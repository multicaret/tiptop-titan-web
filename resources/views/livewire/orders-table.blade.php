<div>
    <style>
        .tableFixHead {
            overflow-y: auto;
            height: {{ ($orders->count() != 0? $orders->count() * 59 :100)+59 }}px;
        }

        .tableFixHead thead th {
            position: sticky;
            top: 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        thead {
            z-index: 999999;
        }

        th, td {
            vertical-align: middle !important;
            text-align: center !important;
            z-index: 1 !important;
        }

        #order-show-modal {
            display: flex;
            justify-content: center;
            align-items: center;
            column-gap: 10px;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <div class="card-text row">
                <div class="col-2">
                    <div class="form-group">
                        <label for="date-filter">Filter By Date</label>
                        <input type="date" wire:model="filterByDate" class="form-control" id="date-filter">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="reference-code">Order Reference Code</label>
                        <input type="text" inputmode="numeric" wire:model.debounce.300ms="referenceCode"
                               class="form-control" id="reference-code">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="customer-name">Customer Name</label>
                        <input type="text" wire:model.debounce.300ms="customerName"
                               class="form-control" id="customer-name">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="customer-email">Customer Email</label>
                        <input type="text" wire:model.debounce.300ms="customerEmail"
                               class="form-control" id="customer-email">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="customer-phone">Customer Phone</label>
                        <input type="text" wire:model.debounce.300ms="customerPhone"
                               class="form-control" id="customer-phone">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="branch-name">Branch Name</label>
                        <input type="text" wire:model.debounce.300ms="branchName"
                               class="form-control" id="branch-name">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="tableFixHead">
            <table class="table card-table table-striped">
                <thead class="thead-dark">
                <tr>
                    <th style="width:10px">#</th>
                    <th>Status</th>
                    <th>Time</th>
                    <th>Type</th>
                    <th>Delivery</th>
                    <th>Customer</th>
                    <th>Branch</th>
                    <th>Total</th>
                    <th>Payment</th>
                </tr>
                </thead>
                <tbody {{--wire:poll.1s--}}>
                @if($orders)
                    @forelse($orders as $order)
                        <tr class="cursor-pointer {{ $order->getLateCssBgClass()}}"
                            data-toggle="modal" data-target="#orderShowModal"
                            wire:click="show({{ $order->id }})">
                            <td style="width:10px">
                                {{$order->reference_code}}
                            </td>
                            <td>
                                <span data-toggle="tooltip" data-placement="top" title="{{$order->getStatusName()}}">
                                @include('admin.orders._partials.statuses.'.$order->status)
                                </span>
                            </td>
                            <td>
                                <h5 class="m-0">
                                    <i class="fas fa-clock"></i>
                                    {{$order->completed_at->format(config('defaults.time.normal_format'))}}
                                    &nbsp;
                                </h5>
                                <small>
                                    <span class="text-lighter">
{{--                                            <i class="far fa-calendar"></i>--}}
                                        {{$order->completed_at->format(config('defaults.date.normal_format'))}}
                                    </span>
                                </small>
                            </td>
                            <td>
                                @if($order->type == \App\Models\Order::CHANNEL_FOOD_OBJECT)
                                    <img src="/images/icons/food-delivery-186/svg/019-food tray.svg"
                                         alt="Food Orders" class="d-inline-block ui-w-20" title="Food">
                                @else
                                    <img src="/images/icons/food-delivery-186/svg/021-food delivery.svg"
                                         alt="Grocery Orders" class="d-inline-block ui-w-20" title="Grocery">
                                @endif
                            </td>
                            <td>
                                @if($order->is_delivery_by_tiptop)
                                    <i class="fas fa-motorcycle text-primary" data-toggle="tooltip" data-placement="top"
                                       title="TipTop"></i>
                                @else
                                    <i class="fas fa-utensils text-success" data-toggle="tooltip" data-placement="top"
                                       title="Restaurant"></i>
                                @endif
                                {{--<lottie-player
                                    src="{{url('animations/motor.json')}}"
                                    background="transparent" speed="2"
                                    style="width: 25px; height: 25px;display: inline-block"
                                    loop hover></lottie-player>--}}
                            </td>
                            <td>
                                @if($order->user->total_number_of_orders == 1)
                                    <span data-toggle="tooltip" data-placement="top"
                                          title="This is their first order ever!">
                                            <b>{{ $order->user->name }}</b>
                                            <lottie-player
                                                src="{{url('animations/new.json')}}"
                                                background="transparent" speed="1"
                                                style="width: 30px; height: 30px;display: inline-block; position: absolute;"
                                                loop
                                                autoplay></lottie-player>
                                    </span>
                                @else
                                    {{ $order->user->name }}
                                @endif
                            </td>
                            <td>
                                {{ $order->branch->title }}
                            </td>
                            <td>
                                {!! \App\Models\Currency::formatHtml($order->grand_total) !!}
                            </td>
                            <td>
                                <span data-toggle="tooltip" data-placement="top"
                                      title="{{$order->paymentMethod->title}}">
                                    <img src="{{$order->paymentMethod->logo}}" alt="{{$order->paymentMethod->title}}"
                                         width="24">
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center">
                                <h4>
                                    No Orders found!
                                </h4>
                                <p>
                                    <button class="btn btn-link btn-outline-primary" wire:click="resetFilters">Reset
                                                                                                               filters?
                                    </button>
                                </p>
                            </td>
                        </tr>
                    @endforelse
                @endif
                </tbody>
            </table>
        </div>
    </div>

    @include('livewire.order-show')

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                $('[data-toggle="tooltip"]').tooltip();
            });
            /*document.addEventListener('livewire:load', function () {
            });*/

            /*window.livewire.on('userStore', () => {
                $('#exampleModal').modal('hide');
            });*/
        </script>
    @endpush
</div>
