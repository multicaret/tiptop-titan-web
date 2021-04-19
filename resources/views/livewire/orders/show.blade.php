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
                               href="{{route('admin.users.edit', $order->user->id)}}">
                                {{$order->user->name}}
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
                               href="tel:{{$order->user->phone_number}}"
                               target="_blank">
                                {{$order->user->phone_number}}
                            </a>
                        </div>
                        <div class="col-12 py-3 border-bottom">
                            <b>Address</b>
                            <a target="_blank" class="text-primary pull-right"
                               href="https://maps.google.com/?ll={{optional($order->address)->latitude}},{{optional($order->address)->longitude}}">
                                {{optional($order->address)->address1}}
                            </a>
                        </div>
                        <div class="col-12 pt-3">
                            <b>Attached to Customer notes</b>
                            <div class="form-group">
                                        <textarea class="form-control" rows="3"
                                                  wire:model.lazy="agentNotes"></textarea>
                            </div>
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
                               href="{{route('admin.branches.edit',[$order->branch->uuid,\App\Models\Branch::getCorrectChannelName($order->branch->type)])}}">
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
                        <div class="col-12 py-3">
                            <b>Primary phone number</b>
                            <a class="text-primary pull-right"
                               href="tel:{{$order->branch->primary_phone_number}}"
                               target="_blank">
                                {{$order->branch->primary_phone_number}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <h5 class="card-header font-weight-bold">
                    <i class="fas fa-sticky-note"></i>&nbsp;
                                                      Customer Notes
                </h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 py-3 border-2bottom">
                            @if($order->notes)
                                {{$order->notes}}
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
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                    </tr>
                    </thead>
                    @if($order->cart)
                        <tbody>
                        @foreach($order->cart->cartProducts as $orderProduct)
                            <tr>
                                <td>
                                    <img src="{{$orderProduct->product_object['cover']}}"
                                         alt="Product cover" width="50">
                                </td>
                                <td>{{$orderProduct->product_object['title']}}</td>
                                <td>{{\App\Models\Currency::formatHtml($orderProduct->product_object['price'])}}</td>
                                <td>{{$orderProduct->quantity}}</td>
                                <td>{{\App\Models\Currency::formatHtml($orderProduct->product_object['price'] * $orderProduct->quantity)}}</td>
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
                    @include('admin.orders._partials.order-activity-log')
                </div>
            </div>
        </div>
    </div>
</div>
