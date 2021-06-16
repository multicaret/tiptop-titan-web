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
                            <option value="{{\App\Models\JetOrder::STATUS_ASSIGNING_COURIER}}">New</option>
                            <option value="{{\App\Models\JetOrder::STATUS_DELIVERED}}">Delivered</option>
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
                    <th>Destination Contact</th>
                    <th>Destination Phone</th>
                    <th>Branch</th>
                    <th>Total</th>
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
                                    @include('admin.jet-orders._partials.statuses.'.$order->status)
                                </span>
                            </td>
                            <td>
                                <h5 class="m-0">
                                    <i class="fas fa-clock"></i>
                                    {{$order->created_at->format(config('defaults.time.normal_format'))}}
                                    &nbsp;
                                </h5>
                                <small>
                                    <span class="text-lighter">
{{--                                            <i class="far fa-calendar"></i>--}}
                                        {{$order->created_at->format(config('defaults.date.normal_format'))}}
                                    </span>
                                </small>
                            </td>
                            <td>
                                    {{ $order->destination_full_name }}
                            </td>
                            <td>
                                {{ $order->destination_phone }}
                            </td>
                            <td>
                                {{ $order->branch->title }}
                            </td>
                            <td>
                                {!! \App\Models\Currency::formatHtml($order->grand_total) !!}
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
                    "{{route('admin.jet.orders.show','xxxx')}}".replace("xxxx", id),
                    "_blank"
                );
            }
        </script>
    @endpush
</div>
