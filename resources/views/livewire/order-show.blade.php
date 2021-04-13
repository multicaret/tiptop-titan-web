<div>
    <!-- Modal -->
    <div wire:ignore class="modal my-auto fade" id="orderShowModal" tabindex="-1" role="dialog"
         aria-labelledby="order-show-modal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            @if(!is_null($selectedOrder))
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="order-show-modal">#
                            {{$selectedOrder->reference_code}}
                            <span data-toggle="tooltip" data-placement="top"
                                  title="{{$selectedOrder->getStatusName()}}">
                                @include('admin.orders._partials.statuses.'.$selectedOrder->status)
                            </span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body bg-light">
                        {{--                    <h1>Modal Body</h1>--}}

                        {{--<div class="form-group">
                            <label for="reference-code">Status</label>
                            <select type="text" inputmode="numeric" --}}{{--wire:model.debounce.300ms="referenceCode"--}}{{--
                            class="form-control" id="reference-code">
                                <option>Preparing</option>
                                <option>Cancelled</option>
                            </select>
                        </div>--}}
                        <div class="nav-tabs-top nav-responsive-xl">
                            <ul class="nav nav-tabs nav-justified">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#navs-bottom-responsive-link-1">
                                        <i class="fas fa-shopping-basket"></i>
                                        &nbsp;Generate details

                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#navs-bottom-responsive-link-2">
                                        <i class="far fa-clipboard"></i>&nbsp;Notes
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#navs-bottom-responsive-link-3">
                                        <i class="far fa-clipboard"></i>
                                        &nbsp;Activity Logs
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content mt-3">
                                <div class="tab-pane fade active show bg-light" id="navs-bottom-responsive-link-1">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header font-weight-bold">User info</div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-12 pb-3 border-bottom">
                                                            <b>Name:</b> <a target="_blank" class="text-primary"
                                                                            href="{{route('admin.users.edit',$selectedOrder->user->id)}}">{{$selectedOrder->user->name}}</a>
                                                        </div>
                                                        <div class="col-12 py-3 border-bottom">
                                                            <b>Email:</b>
                                                            <a class="text-primary" target="_blank"
                                                               href="mailto:{{$selectedOrder->user->email}}">
                                                                {{$selectedOrder->user->email}}
                                                            </a>
                                                        </div>
                                                        <div class="col-12 py-3 border-bottom">
                                                            <b>Phone:</b> {{$selectedOrder->user->phone_number}}
                                                        </div>
                                                        <div class="col-12 py-3 border-bottom">
                                                            <b>Address:</b>
                                                            <a target="_blank" class="text-primary"
                                                               href="https://maps.google.com/?ll={{$selectedOrder->address->latitude}},{{$selectedOrder->address->longitude}}">
                                                                {{optional($selectedOrder->address)->address1}}
                                                            </a>
                                                        </div>
                                                        <div class="col-12 py-3 border-bottom"
                                                             style="height: 110px;overflow:scroll">
                                                            <b>Notes:</b> {{$selectedOrder->notes}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header font-weight-bold">Brunch details</div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-12 pb-3 border-bottom">
                                                            <b>Name:</b> <a class="text-primary"
                                                                            target="_blank"
                                                                            href="{{route('admin.branches.edit',[$selectedOrder->branch->uuid,\App\Models\Branch::getCorrectChannelName($selectedOrder->branch->type)])}}">
                                                                {{$selectedOrder->branch->title}}
                                                            </a>
                                                        </div>
                                                        <div class="col-12 py-3 border-bottom">
                                                            <b>Email:</b>
                                                            <a class="text-primary" target="_blank"
                                                               href="mailto:{{$selectedOrder->user->email}}">
                                                                {{$selectedOrder->user->email}}
                                                            </a>
                                                        </div>
                                                        <div class="col-12 py-3 border-bottom">
                                                            <b>Primary phone number</b>
                                                            : <a
                                                                class="text-primary"
                                                                href="tel:{{$selectedOrder->branch->primary_phone_number}}"
                                                                target="_blank">{{$selectedOrder->branch->primary_phone_number}}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mt-4">
                                        <div class="card-header font-weight-bold">Products</div>
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
                                            <tbody>
                                            @foreach($selectedOrder->cart->cartProducts as $orderProduct)
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
                                        </table>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header font-weight-bold">Prices</div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12 pb-3 border-bottom">
                                                            <b>Total:</b>
                                                            {!! \App\Models\Currency::formatHtml($selectedOrder->total) !!}
                                                        </div>
                                                        @if(!is_null($selectedOrder->coupon_id))
                                                            <div class="col-md-12 py-3 border-bottom">
                                                                <b>Coupon discount amount:</b>
                                                                {!! \App\Models\Currency::formatHtml($selectedOrder->coupon_discount_amount) !!}
                                                            </div>
                                                            <div class="col-md-12 py-3 border-bottom">
                                                                <b>Coupon Code:</b>
                                                                {{$selectedOrder->coupon->redeem_code}}
                                                            </div>
                                                        @endif
                                                        <div class="col-md-12 py-3 border-bottom">
                                                            <b>Delivery fee:</b>
                                                            {!! \App\Models\Currency::formatHtml($selectedOrder->delivery_fee) !!}
                                                        </div>
                                                        <div class="col-md-12 py-3 border-bottom">
                                                            <b>Grand total:</b>
                                                            {!! \App\Models\Currency::formatHtml($selectedOrder->grand_total) !!}
                                                        </div>
                                                        <div class="col-md-12 py-3 border-bottom">
                                                            <b>Payment method</b>
                                                            : {{$selectedOrder->paymentMethod->title}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header font-weight-bold">Timeline</div>
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

                                </div>
                                <div class="tab-pane fade" id="navs-bottom-responsive-link-2">
                                    @include('admin.orders._partials.order-agent-notes')
                                </div>
                                <div class="tab-pane fade" id="navs-bottom-responsive-link-3">
                                    <div class="card-body">
                                        @include('admin.orders._partials.order-activity-log')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click.prevent="$set('showModal','false')" class="btn btn-secondary"
                                data-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
