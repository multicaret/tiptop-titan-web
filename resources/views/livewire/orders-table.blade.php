<div>
    <style>
        .tableFixHead {
            overflow-y: auto;
            height: {{ ($orders->count() != 0? $orders->count() * 57 :100)+57 }}px;
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

        td {
            z-index: -999;
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
                        <label for="branch-name">Branch</label>
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
                    <th>Status</th>
                    <th>#</th>
                    <th>Time</th>
                    <th>Type</th>
                    <th>Delivery Type</th>
                    <th>Customer Name</th>
                    <th>Branch</th>
                    <th>Total</th>
                    <th>Payment Method</th>
                </tr>
                </thead>
                <tbody {{--wire:poll.1s--}}>
                @if($orders)
                    @forelse($orders as $order)
                        <tr class="{{ $order->getLateCssBgClass()}}">
                            <td>
                        <span data-toggle="tooltip" data-placement="top" title="{{$order->status}}">
                        @include('admin.orders._partials.statuses.'.$order->status)
                        </span>
                            </td>
                            <th scope="row">
                                {{$order->reference_code}}
                            </th>
                            <td>
                                <h5>
                                    <i class="far fa-clock"></i>
                                    {{$order->completed_at->format(config('defaults.time.normal_format'))}}
                                    &nbsp;
                                    <small>
                                <span class="text-muted">
                                    <i class="far fa-calendar"></i>
                                    {{$order->completed_at->format(config('defaults.date.normal_format'))}}
                                </span>
                                    </small>

                                </h5>
                            </td>
                            <td>Grocery</td>
                            <td>
                                {{--                        <i class="fas fa-motorcycle text-primary"></i>--}}
                                <lottie-player
                                    src="{{url('animations/motor.json')}}"
                                    background="transparent" speed="2"
                                    style="width: 25px; height: 25px;display: inline-block"
                                    loop hover></lottie-player>
                                TipTop Delivery
                            </td>
                            <td>
                                @if($order->user->total_number_of_orders == 1)
                                    <span data-toggle="tooltip" data-placement="top"
                                          title="This is their first order ever!">
                                            <b>{{ $order->user->name }}</b>
                                            <lottie-player
                                                src="{{url('animations/new.json')}}"
                                                background="transparent" speed="1"
                                                style="width: 30px; height: 30px;display: inline-block" loop
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
                            <td colspan="9" class="text-center">
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
            /*document.addEventListener('livewire:load', function () {
            });*/
        </script>
    @endpush
</div>
