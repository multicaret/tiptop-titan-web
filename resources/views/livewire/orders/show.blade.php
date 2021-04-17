<div>
    <div class="card mb-4 shadow-lg">
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
        <div class="card-body  bg-light">
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <h5 class="card-header font-weight-bold">
                            #{{$order->reference_code}}
                            |
                            <span class="text-muted">
                                Timeline
                            </span>
                            <div class="d-inline-block">
                                @if($order->getPermittedStatus())
                                    <select type="text" wire:model="order.status" class="form-control"
                                            id="order-status-top">
                                        <option selected>Please Select</option>
                                        @foreach($order->getPermittedStatus() as $status)
                                            <option value="{{$status['id']}}">{{$status['title']}}</option>
                                        @endforeach
                                    </select>
                                @else
                                    Status Cannot be changed!
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
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 pb-3 border-bottom">
                                    <b>Name:</b> <a target="_blank" class="text-primary"
                                                    href="{{route('admin.users.edit',$order->user->id)}}">{{$order->user->name}}</a>
                                </div>
                                <div class="col-12 py-3 border-bottom">
                                    <b>Email:</b>
                                    <a class="text-primary" target="_blank"
                                       href="mailto:{{$order->user->email}}">
                                        {{$order->user->email}}
                                    </a>
                                </div>
                                <div class="col-12 py-3 border-bottom">
                                    <b>Phone:</b> {{$order->user->phone_number}}
                                </div>
                                <div class="col-12 py-3 border-bottom">
                                    <b>Address:</b>
                                    <a target="_blank" class="text-primary"
                                       href="https://maps.google.com/?ll={{optional($order->address)->latitude}},{{optional($order->address)->longitude}}">
                                        {{optional($order->address)->address1}}
                                    </a>
                                </div>
                                <div class="col-12 py-3 border-bottom"
                                     style="height: 110px;overflow:scroll">
                                    <b>Notes:</b> {{$order->notes}}
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
                                    <b>Name:</b> <a class="text-primary"
                                                    target="_blank"
                                                    href="{{route('admin.branches.edit',[$order->branch->uuid,\App\Models\Branch::getCorrectChannelName($order->branch->type)])}}">
                                        {{$order->branch->title}}
                                    </a>
                                </div>
                                <div class="col-12 py-3 border-bottom">
                                    <b>Email:</b>
                                    <a class="text-primary" target="_blank"
                                       href="mailto:{{$order->user->email}}">
                                        {{$order->user->email}}
                                    </a>
                                </div>
                                <div class="col-12 py-3 border-bottom">
                                    <b>Primary phone number</b>
                                    : <a
                                        class="text-primary"
                                        href="tel:{{$order->branch->primary_phone_number}}"
                                        target="_blank">{{$order->branch->primary_phone_number}}</a>
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
                                        <td>{{$orderProduct->product_object['price']}}</td>
                                        <td>{{$orderProduct->quantity}}</td>
                                        <td>{{($orderProduct->product_object['price'] * $orderProduct->quantity)}}</td>
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

        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="card mb-4 shadow-lg">
                <h4 class="card-header">
                    <i class="far fa-clipboard"></i>
                    &nbsp;Notes ({{$order->agentNotes()->count()}})
                </h4>
                @include('admin.orders._partials.order-agent-notes')
            </div>
        </div>
        <div class="col-6">
            <div class="card mb-4 shadow-lg">
                <h4 class="card-header">
                    <i class="far fa-chart-bar"></i>
                    &nbsp;Activity Log
                </h4>
                <div class="card-body">
                    @include('admin.orders._partials.order-activity-log')
                </div>
            </div>
        </div>
    </div>
</div>
