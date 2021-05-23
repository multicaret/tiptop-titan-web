@extends('layouts.admin')
@section('title', 'Orders')
@section('content')
    <div>
        <div class="mb-4">
            <div class="row mb-3">
                <div class="col-3">
                    <div class="card">
                        <div class="card-body shadow px-4 py-3 rounded-lg">
                            <h5 class="card-title text-secondary">
                                New Orders
                            </h5>
                            <div class="card-text">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-concierge-bell fa-2x text-secondary"></i>
                                    <div class="media-body ml-4 text-secondary align-self-center">
                                        <h5 class="m-0">
                                            {{App\Models\Order::foods()->new()->count()}}
                                        </h5>
                                    </div>
                                    <i class="fas fa-shopping-basket fa-2x text-primary"></i>
                                    <div class="media-body ml-4 text-secondary align-self-center">
                                        <h5 class="m-0">
                                            {{App\Models\Order::groceries()->new()->count()}}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="card">
                        <div class="card-body shadow px-4 py-3 rounded-lg">
                            <h5 class="card-title text-secondary">
                                Preparing Orders
                            </h5>
                            <div class="card-text">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-concierge-bell fa-2x text-secondary"></i>
                                    <div class="media-body ml-4 text-secondary align-self-center">
                                        <h5 class="m-0">
                                            {{App\Models\Order::foods()->preparing()->count()}}
                                        </h5>
                                    </div>
                                    <i class="fas fa-shopping-basket fa-2x text-primary"></i>
                                    <div class="media-body ml-4 text-secondary align-self-center">
                                        <h5 class="m-0">
                                            {{App\Models\Order::groceries()->preparing()->count()}}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="card">
                        <div class="card-body shadow px-4 py-3 rounded-lg">
                            <h5 class="card-title text-secondary">
                                Waiting Courier Orders
                            </h5>
                            <div class="card-text">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-concierge-bell fa-2x text-secondary"></i>
                                    <div class="media-body ml-4 text-secondary align-self-center">
                                        <h5 class="m-0">
                                            {{App\Models\Order::foods()->waitingCourier()->count()}}
                                        </h5>
                                    </div>
                                    <i class="fas fa-shopping-basket fa-2x text-primary"></i>
                                    <div class="media-body ml-4 text-secondary align-self-center">
                                        <h5 class="m-0">
                                            {{App\Models\Order::groceries()->waitingCourier()->count()}}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="card">
                        <div class="card-body shadow px-4 py-3 rounded-lg">
                            <h5 class="card-title text-secondary">
                                On the way Orders
                            </h5>
                            <div class="card-text">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-concierge-bell fa-2x text-secondary"></i>
                                    <div class="media-body ml-4 text-secondary align-self-center">
                                        <h5 class="m-0">
                                            {{App\Models\Order::foods()->onTheWay()->count()}}
                                        </h5>
                                    </div>
                                    <i class="fas fa-shopping-basket fa-2x text-primary"></i>
                                    <div class="media-body ml-4 text-secondary align-self-center">
                                        <h5 class="m-0">
                                            {{App\Models\Order::groceries()->onTheWay()->count()}}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-4 mt-2">
                    <div class="card">
                        <div class="card-body shadow px-4 py-3 rounded-lg">
                            <h5 class="card-title text-secondary">
                                At the address Orders
                            </h5>
                            <div class="card-text">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-concierge-bell fa-2x text-secondary"></i>
                                    <div class="media-body ml-4 text-secondary align-self-center">
                                        <h5 class="m-0">
                                            {{App\Models\Order::foods()->atTheAddress()->count()}}
                                        </h5>
                                    </div>
                                    <i class="fas fa-shopping-basket fa-2x text-primary"></i>
                                    <div class="media-body ml-4 text-secondary align-self-center">
                                        <h5 class="m-0">
                                            {{App\Models\Order::groceries()->atTheAddress()->count()}}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-4 mt-2">
                    <div class="card">
                        <div class="card-body shadow px-4 py-3 rounded-lg">
                            <h5 class="card-title text-secondary">
                                Delivered Orders
                            </h5>
                            <div class="card-text">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-concierge-bell fa-2x text-secondary"></i>
                                    <div class="media-body ml-4 text-secondary align-self-center">
                                        <h5 class="m-0">
                                            {{App\Models\Order::foods()->delivered()->count()}}
                                        </h5>
                                    </div>
                                    <i class="fas fa-shopping-basket fa-2x text-primary"></i>
                                    <div class="media-body ml-4 text-secondary align-self-center">
                                        <h5 class="m-0">
                                            {{App\Models\Order::groceries()->delivered()->count()}}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4 mt-2">
                    <div class="card">
                        <div class="card-body shadow px-4 py-3 rounded-lg">
                            <h5 class="card-title text-secondary">
                                Cancelled Orders
                            </h5>
                            <div class="card-text">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-concierge-bell fa-2x text-secondary"></i>
                                    <div class="media-body ml-4 text-secondary align-self-center">
                                        <h5 class="m-0">
                                            {{App\Models\Order::foods()->cancelled()->count()}}
                                        </h5>
                                    </div>
                                    <i class="fas fa-shopping-basket fa-2x text-primary"></i>
                                    <div class="media-body ml-4 text-secondary align-self-center">
                                        <h5 class="m-0">
                                            {{App\Models\Order::groceries()->cancelled()->count()}}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div>
                @push('styles')
                    <style>
                        .table-fixed-head {
                            overflow-y: auto;
                            {{--height: {{ ($orders->count() != 0? $orders->count() * 59 :100)+59 }}px;--}}
                   height: 200vh;
                        }

                        .table-fixed-head thead th {
                            position: sticky;
                            top: 0;
                        }
                    </style>
                @endpush
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
                            <tbody>
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
                                        </td>
                                    </tr>
                                @endforelse
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-12 text-center">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://unpkg.com/hotkeys-js/dist/hotkeys.min.js"></script>
    <script type="text/javascript">
        hotkeys('ctrl+r,r', function (event, handler) {
            switch (handler.key) {
                case 'ctrl+r':
                case 'r':
                    window.location.reload();
                    break;
                default:
                    alert(event);
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        function openOrder(id) {
            window.open(
                "{{route('admin.orders.show','xxxx')}}".replace("xxxx", id),
                "_blank"
            );
        }
    </script>
@endpush
