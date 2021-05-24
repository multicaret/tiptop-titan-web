<div>
    @push('styles')
        <style>
            .table-fixed-head {
                overflow-y: auto;
                {{--height: {{ ($orders->count() != 0? $orders->count() * 59 :100)+59 }}px;--}}
                       height: 50vh;
            }

            .table-fixed-head thead th {
                position: sticky;
                top: 0;
            }
        </style>
    @endpush
    <div class="card">
        <div class="card-body">
            <div class="card-text row">
                <div class="col-3">
                    <div class="form-group">
                        <label for="date-filter">Filter By Date</label>
                        <input type="date" wire:model="filterByDate" class="form-control" id="date-filter">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="reference-code">Order Reference Code</label>
                        <input type="text" inputmode="numeric" wire:model.debounce.300ms="referenceCode"
                               class="form-control" id="reference-code">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="customer-name">Customer Name</label>
                        <input type="text" wire:model.debounce.300ms="customerName"
                               class="form-control" id="customer-name">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="customer-email">Customer Email</label>
                        <input type="text" wire:model.debounce.300ms="customerEmail"
                               class="form-control" id="customer-email">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="customer-phone">Customer Phone</label>
                        <input type="text" wire:model.debounce.300ms="customerPhone"
                               class="form-control" id="customer-phone">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="branch-name">Branch Name</label>
                        <input type="text" wire:model.debounce.300ms="branchName"
                               class="form-control" id="branch-name">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="branch-name">Status</label>
                        <select wire:model="searchByStatus" class="form-control">
                            <option value="">All</option>
                            <option value="{{\App\Models\Order::STATUS_NEW}}">New</option>
                            <option value="{{\App\Models\Order::STATUS_PREPARING}}">Preparing</option>
                            <option value="{{\App\Models\Order::STATUS_DELIVERED}}">Delivered</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="table-fixed-head" style="{{$orders->count() == 0 ?'height:150px;':''}}">
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
                <tbody wire:poll.20s>
                @if($orders)
                    @forelse($orders as $order)
                        <tr class="cursor-pointer {{ $order->getLateCssBgClass()}}"
                            onclick="openOrder({{$order->id}})">
                            <td style="width:10px">
                                {{$order->reference_code}}
                            </td>
                            <td>
                                <span data-toggle="tooltip" data-placement="top" title="{{$order->getStatusName()}}"
                                      key="{{$order->status}}">
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
                                @if($order->type == \App\Models\Order::CHANNEL_GROCERY_OBJECT)
                                    {{--<img src="/images/icons/food-delivery-186/svg/019-food tray.svg"
                                         alt="Food Orders" class="d-inline-block ui-w-20" title="Food">--}}
                                    <i class="fas fa-shopping-basket fa-1x text-success"></i>
                                @else
                                    {{-- <img src="/images/icons/food-delivery-186/svg/021-food delivery.svg"
                                          alt="Grocery Orders" class="d-inline-block ui-w-20" title="Grocery">--}}
                                    <i class="fas fa-concierge-bell fa-1x text-primary"></i>
                                @endif
                            </td>
                            <td>
                                @if($order->is_delivery_by_tiptop)
                                    <i class="fas fa-motorcycle text-primary" data-toggle="tooltip"
                                       data-placement="top"
                                       title="TipTop"></i>
                                @else
                                    <i class="fas fa-utensils text-success" data-toggle="tooltip"
                                       data-placement="top"
                                       title="Restaurant"></i>
                                @endif
                            </td>
                            <td>
                                {{--                                {{dd($order->user_id)}}--}}
                                @if($order->user->total_number_of_orders == 1)
                                    <span data-toggle="tooltip" data-placement="top"
                                          title="This is their first order ever!"
                                          style="position: relative">
                                            <b>{{ $order->user->name }}</b>
                                           <span class="badge badge-pill badge-warning">
                                                NEW
                                            </span>
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                $('[data-toggle="tooltip"]').tooltip();
            });

            /* document.addEventListener('livewire:load', function () {
             });*/
            function openOrder(id) {
                window.open(
                    "{{route('admin.orders.show','xxxx')}}".replace("xxxx", id),
                    "_blank"
                );
            }
        </script>
    @endpush
</div>
