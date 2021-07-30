<div>

    <style>
        .card {
            transition: all 1s;
        }

        .card:hover {
            box-shadow: 0 1rem 3rem rgba(24, 28, 33, .18) !important;
        }
    </style>
    {{--<h4 class="card-header font-weight-bold d-flex justify-content-between">
        <small class="d-flex align-items-center">
            {{$order->getStatusName()}} &nbsp;
            <span class="d-inline-block">
                @include('admin.orders._partials.statuses.'.$order->status)
            </span>
        </small>
        <div class="pull-right">

        </div>
    </h4>--}}
    @php([$statusesIntervals,$total] = $order->getStatusesIntervals())
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header font-weight-bold">
                    <div class="row">
                        <div class="col-12 d-flex align-items-center justify-content-start">
                            <span class="text-muted">Timeline of Order</span>
                            <span>#{{$order->reference_code}}</span>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            Status&nbsp;
                            <div class="d-inline-block">
                                <select type="text" wire:model="order.status" class="form-control"
                                        id="order-status-top">
                                    <option selected disabled>Please Select</option>
                                    <option hidden>Please Select</option>
                                    @foreach($order->getPermittedStatus() as $status)
                                        <option value="{{$status['id']}}">{{$status['title']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="ml-3">
                                <span class="text-muted">Order Lifespan</span>
                                <span>
                                    {{\Carbon\CarbonInterval::seconds($total)->cascade()->forHumans()}}
                                </span>
                            </small>
                        </div>
                        @if($isCancellationFormShown)
                            <div class="col-12 mt-2">
                                <form class="d-flex align-items-center justify-content-space"
                                      wire:submit.prevent="storeCancellationReason">
                                    <div class="text-danger mr-2">Cancellation</div>
                                    <div class="mr-2">
                                        <select type="text" wire:model="order.cancellation_reason_id"
                                                class="form-control"
                                                id="order-status-top">
                                            <option selected>Please Select</option>
                                            @foreach($cancellationReasons as $reason)
                                                <option value="{{$reason->id}}">{{$reason->title}}</option>
                                            @endforeach
                                            <option value="0">Other</option>
                                        </select>
                                        @error('order.cancellation_reason_id')
                                        <small class="form-text text-danger">
                                            {{$message}}
                                        </small>
                                        @enderror
                                    </div>
                                    <input class="form-control mr-2 w-75" wire:model="order.cancellation_reason_note"
                                           placeholder="Cancelling note">
                                    @error('order.cancellation_reason_note')
                                    <small class="form-text text-danger">
                                        {{$message}}
                                    </small>
                                    @enderror
                                    <button class="btn btn-outline-success btn-2sm" type="submit">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            @include('admin.orders._partials.statuses.timeline')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <h5 class="card-header font-weight-bold">
                    <i class="fas fa-user"></i>&nbsp;
                                               User info
                </h5>
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-12 pb-3 border-bottom">
                            <b>Name</b>
                            <a target="_blank" class="text-primary pull-right"
                               href="{{route('admin.users.edit', ['role' => $order->user->role_name, 'user' => $order->user])}}">
                                {{$order->user->name}}

                                @if($order->user->orders()->count() == 1)
                                    <div class="pl-1 ml-auto bounce d-inline-block" style="font-size:16px;">
                                        <div class="badge badge-danger d-inline-block">
                                            New
                                        </div>
                                    </div>
                                @endif
                            </a>
                        </div>
                        <div class="col-12 py-3 border-bottom">
                            <b>Email</b>
                            <a class="text-primary pull-right" target="_blank"
                               href="mailto:{{$order->user->email}}">
                                {{$order->user->email}}
                            </a>
                        </div>
                        <div class="col-12 py-3 border-bottom">
                            <b>Phone</b>
                            <a class="text-primary pull-right"
                               href="tel:00{{$order->user->phone_country_code}}{{$order->user->phone_number}}"
                               target="_blank">
                                +{{$order->user->phone_country_code}}{{$order->user->phone_number}}
                            </a>
                        </div>
                        <div class="col-12 py-3 border-bottom">
                            <b>Address</b>
                            <a target="_blank" class="text-primary pull-right"
                               href="https://maps.google.com/?q={{optional($order->address)->latitude}},{{optional($order->address)->longitude}}">
                                {{optional($order->address)->address1}}
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                        <div class="col-12 pt-3 pb-4">
                            <b>Attached to Customer notes</b>
                            <div class="form-group">
                                        <textarea class="form-control" rows="3"
                                                  wire:model.lazy="agentNotes"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <h5 class="card-header font-weight-bold">
                    <i class="fas fa-sticky-note"></i>&nbsp;
                                                      Order Customer Notes
                </h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 py-3 border-2bottom">
                            @if($order->customer_notes)
                                {{$order->customer_notes}}
                            @else
                                <i class="text-muted">
                                    NO NOTE
                                </i>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <h5 class="card-header font-weight-bold">
                    <i class="fas fa-code-branch"></i>&nbsp;
                                                      Branch details
                </h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 pb-3 border-bottom">
                            <b>Name</b>
                            <a class="text-primary pull-right"
                               target="_blank"
                               href="{{route('admin.branches.edit',[$order->branch->uuid,'type' => \App\Models\Branch::getCorrectChannelName($order->branch->type)])}}">
                                {{optional($order->branch->region)->name}} -
                                {{optional($order->branch->city)->name}}
                                {{$order->branch->title}}
                            </a>
                        </div>
                        <div class="col-12 py-3 border-bottom">
                            <b>Email</b>
                            <a class="text-primary pull-right" target="_blank"
                               href="mailto:{{$order->user->email}}">
                                {{$order->user->email}}
                            </a>
                        </div>
                        <div class="col-12 py-3 border-bottom">
                            <b>Primary phone number</b>
                            <a class="text-primary pull-right"
                               href="tel:{{$order->branch->primary_phone_number}}"
                               target="_blank">
                                {{$order->branch->primary_phone_number}}
                            </a>
                        </div>
                        <div class="col-12 py-3">
                            <b>Address</b>
                            <a class="text-primary pull-right"
                               href="https://maps.google.com/?q={{$order->branch->latitude}},{{$order->branch->longitude}}"
                               target="_blank">
                                Open In Google Maps <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <h5 class="card-header font-weight-bold">
                    <i class="fas fa-motorcycle"></i>&nbsp;
                                                     Delivery info
                </h5>
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-12 pb-3 border-bottom">
                            <b>Delivery Type</b>

                            <p class="text-muted  pull-right">{{$order->is_delivery_by_tiptop ? 'TipTop' : 'Restaurant'}}</p>
                        </div>
                        <div class="col-12 pb-3 border-bottom">
                            <b>Driver</b>
                            @if(!empty($order->driver))
                                <a target="_blank" class="text-primary pull-right"
                                   href="{{route('admin.users.edit', ['role' => $order->driver->role_name, 'user' => $order->driver])}}">
                                    {{$order->driver->name}}
                                </a>
                            @else
                                <p class="text-muted  pull-right">Waiting Delivery information</p>
                            @endif
                        </div>
                        <div class="col-12 py-3 border-bottom" style="padding-bottom: 1.3rem !important;">
                            <b>Phone</b>
                            @if(!empty($order->driver))

                                <a class="text-primary pull-right"
                                   href="tel:{{$order->user->phone_number}}"
                                   target="_blank">
                                    {{$order->driver->phone_number}}
                                </a>
                            @endif
                        </div>

                        @if(!empty($order->driver) && !in_array($order->status,[\App\Models\Order::STATUS_DELIVERED,\App\Models\Order::STATUS_CANCELLED]) && !empty($order->tookanInfo->pickup_tracking_link) && !empty($order->tookanInfo->delivery_tracking_link))
                            <div class="col-12 py-3 border-bottom">
                                <b>Pickup Task ID</b>
                                <p class="text-primary pull-right">
                                    {{$order->tookanInfo->job_pickup_id}}
                                </p>
                            </div>
                            <div class="col-12 py-3 border-bottom">
                                <b>Delivery Task ID</b>
                                <p class="text-primary pull-right">
                                    {{$order->tookanInfo->job_delivery_id}}
                                </p>
                            </div>
                            <div class="col-12 py-3 border-bottom">
                                <b>Pickup Tracking</b>
                                <a class="text-primary pull-right" target="_blank"
                                   href="{{$order->tookanInfo->pickup_tracking_link}}">
                                    <i class="fas fa-external-link-alt text-warning"></i>
                                </a>
                            </div>
                            <div class="col-12 py-3 border-bottom">
                                <b>Delivery Tracking</b>
                                <a class="text-primary pull-right" target="_blank"
                                   href="{{$order->tookanInfo->delivery_tracking_link}}">
                                    <i class="fas fa-external-link-alt text-warning"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-8">
            <div class="card">
                <h5 class="card-header font-weight-bold">
                    @if($order->is_grocery)
                        <i class="fas fa-carrot"></i>&nbsp;
                    @else
                        <i class="fas fa-pizza-slice"></i>&nbsp;
                    @endif
                                                     Products
                </h5>
                <table class="table card-table">
                    <thead class="thead-light">
                    <tr>
                        <th>Thumbnail</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Unit</th>
                        <th>Original Price</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                    </tr>
                    </thead>
                    @if($order->cart)
                        <tbody>
                        @foreach($order->cart->cartProducts()->with('cartProductOptions')->get() as $orderProduct)
                            <tr>
                                <td>
                                    @if(isset($orderProduct->product_object['cover']))
                                        <img src="{{$orderProduct->product_object['cover']}}"
                                             alt="Product cover" width="50">
                                    @endif
                                </td>
                                <td>
                                    <span data-toggle="tooltip" data-placement="top"
                                          title="{{collect($orderProduct->product_object['translations'])->pluck('title','locale')->get('en')}}"
                                          class="d-block">{{$orderProduct->product_object['title']}}
                                    </span>
                                    @if($orderProduct->cartProductOptions()->count())
                                        @foreach($orderProduct->cartProductOptions as $cartProductOption)
                                            <div class="d-block">
                                                <span class="mr-1">
                                                {{$cartProductOption->product_option_object['title']}}
                                                </span>:
                                                @foreach($cartProductOption->selections as $selection)
                                                    <a href="#!"
                                                       class="badge badge-pill badge-warning mr-1">
                                                        {{$selection->selectable_object['title']}}
                                                        @if(array_key_exists('price',$selection->selectable_object) && $selection->selectable_object['price'])
                                                            {{$selection->selectable_object['price']}}
                                                        @endif
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    {{$orderProduct->product_object['description']}}
                                </td>
                                <td>
                                    @if($orderProduct->product_object['unit_id'])
                                        {{ \App\Models\Taxonomy::find($orderProduct->product_object['unit_id'])->title }}
                                    @endif
                                    &nbsp; - &nbsp;{{$orderProduct->product_object['unit_text']}}
                                </td>
                                <td>{!! \App\Models\Currency::formatHtml($orderProduct->product_object['price'] + $orderProduct->options_price) !!}</td>
                                <td>{!! \App\Models\Currency::formatHtml($orderProduct->product_object['discounted_price'] + $orderProduct->options_price) !!}</td>
                                <td>{{$orderProduct->quantity}}</td>
                                <td>{!! \App\Models\Currency::formatHtml(($orderProduct->product_object['discounted_price'] * $orderProduct->quantity) + $orderProduct->total_options_price) !!}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    @else
                        <tbody>
                        <tr>
                            <td colspan="5" class="text-center p-5">
                                <em>This is an old order without a cart</em>
                            </td>
                        </tr>
                        </tbody>
                    @endif
                </table>

            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <h5 class="card-header font-weight-bold">
                    <i class="fas fa-coins"></i>&nbsp;
                                                Prices
                </h5>
                <div class="card-body pb-1">
                    <div class="row">
                        <div class="col-md-12 pb-3 border-bottom">
                            <b>Total</b>
                            <div class="pull-right">
                                {!! \App\Models\Currency::formatHtml($order->total) !!}
                            </div>
                        </div>
                        @if(!is_null($order->coupon_id))
                            <div class="col-md-12 py-3 border-bottom">
                                <b>Coupon discount amount</b>
                                <div class="pull-right">
                                    {!! \App\Models\Currency::formatHtml($order->coupon_discount_amount) !!}
                                </div>
                            </div>
                            <div class="col-md-12 py-3 border-bottom">
                                <b>Coupon Code</b>
                                <div class="pull-right">
                                    {{$order->coupon->redeem_code}}
                                </div>
                            </div>
                        @endif
                        <div class="col-md-12 py-3 border-bottom">
                            <b>Delivery fee</b>
                            <div class="pull-right">
                                {!! \App\Models\Currency::formatHtml($order->delivery_fee) !!}
                            </div>
                        </div>
                        <div class="col-md-12 py-3 border-bottom border-light border bg-primary">
                            <button class="btn icon-btn btn-outline-secondary btn-sm"
                                    wire:click="toggleGrandTotalForm">
                                <i class="fas fa-pen"></i>
                            </button>
                            <b>Grand total</b>
                            <div class="pull-right">
                                {!! \App\Models\Currency::formatHtml($order->grand_total) !!}
                            </div>
                        </div>
                        @if($isGrantTotalFormShown)
                            <div class="col-md-12 py-3 border-bottom">
                                <form wire:submit.prevent="addNewGrandTotal">
                                    <div class="form-group">
                                        <label class="control-label" for="new-grand-total">
                                            New Grand Total (optional)
                                        </label>
                                        <input type="number" min="1" step="any" id="new-grand-total"
                                               class="form-control"
                                               placeholder="{{$order->grand_total}}"
                                               wire:model.lazy="newGrandTotal">
                                        @error('newGrandTotal')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Type your message"
                                               wire:model="newGrandTotalNote">

                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">Send</button>
                                        </div>
                                    </div>
                                    @error('newGrandTotalNote')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </form>
                            </div>
                        @endif
                        <div class="col-md-12 py-3">
                            <b>Payment method</b>
                            <div class="pull-right"><img src="{{$order->paymentMethod->logo}}" width="20px"
                                                         alt="{{$order->paymentMethod->title}}">
                                {{$order->paymentMethod->title}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row mt-4">
        <div class="col-6">
            <div class="card mb-4">
                <h4 class="card-header">
                    <i class="far fa-clipboard"></i>
                    &nbsp;Notes ({{$order->agentNotes()->count()}})
                </h4>
                @include('admin.orders._partials.order-agent-notes')
            </div>
        </div>
        <div class="col-6">
            <div class="card mb-4">
                <h4 class="card-header">
                    <i class="far fa-chart-bar"></i>
                    &nbsp;Activity Log
                </h4>
                <div class="card-body p-0 pl-2">
                    @include('admin.partials.activity-logs',['object' => $order])
                </div>
            </div>
        </div>
    </div>
</div>
