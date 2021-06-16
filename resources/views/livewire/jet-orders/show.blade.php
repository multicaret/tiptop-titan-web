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
                            <p class="text-primary pull-right">
                                {{$order->destination_full_name}}
                            </p>
                        </div>
                        <div class="col-12 py-3 border-bottom">
                            <b>Phone</b>
                            <p class="text-primary pull-right">
                                {{$order->destination_phone}}
                            </p>
                        </div>
                        <div class="col-12 py-3 border-bottom">
                            <b>Address</b>
                            <a target="_blank" class="text-primary pull-right"
                               href="https://maps.google.com/?q={{$order->destination_latitude}},{{$order->destination_longitude}}">
                                {{$order->destination_address}}
                                <i class="fas fa-external-link-alt"></i>
                            </a>
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
                            @if($order->client_notes)
                                    {{$order->client_notes}}
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

            @if($order->is_delivery_by_tiptop)
                <div class="card mt-3">
                    <h5 class="card-header font-weight-bold">
                        <i class="fas fa-motorcycle"></i>&nbsp;
                                                         Delivery info
                    </h5>
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-12 pb-3 border-bottom">
                                <b>Driver</b>
                                @if(!empty($order->driver))
                                    <a target="_blank" class="text-primary pull-right"
                                       href="{{route('admin.users.edit', ['role' => $order->driver->role_name, 'user' => $order->driver])}}">
                                        {{$order->driver->name}}
                                    </a>
                                @else
                                    <p class="text-muted">Waiting Delivery information</p>
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
            @endif
        </div>
    </div>


    <div class="row mt-4">
        <div class="col-6">
            <div class="card mb-4">
                <h4 class="card-header">
                    <i class="far fa-clipboard"></i>
                    &nbsp;Notes
                </h4>
{{--
                @include('admin.orders._partials.order-agent-notes')
--}}
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
