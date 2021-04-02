<div>
    <div class="card">
        {{--        <div class="card-header">Orders</div>--}}
        <table class="table card-table table-striped">
            <thead class="thead-light">
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
            <tbody>
            @forelse($orders as $order)
                <tr class="{{ $order->id == 5 ?'bg-warning':''}}">
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
                            background="transparent" speed="2" style="width: 25px; height: 25px;display: inline-block"
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
                        <span data-toggle="tooltip" data-placement="top" title="{{$order->paymentMethod->title}}">
                            <img src="{{$order->paymentMethod->logo}}" alt="{{$order->paymentMethod->title}}"
                                 width="24">
                        </span>
                    </td>
                </tr>
            @empty
            @endforelse
            </tbody>
        </table>
        <br>
        <div class="row d-flex  align-content-center">
            <div class="col-12 align-self-center">
                {{$orders->links()}}
            </div>
        </div>
    </div>
</div>
