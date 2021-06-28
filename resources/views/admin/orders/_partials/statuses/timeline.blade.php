<ul class="order-timeline">
    @foreach($order->getAllStatuses() as $status)
        <li class="{{$status['isSelected']? 'text-primary':''}}">
            <small class="timeline-timestamp text-danger" style="position: absolute;bottom: 45px;">
                @if(isset($statusesIntervals[$status['id']]))
                    {{\Carbon\CarbonInterval::seconds($statusesIntervals[$status['id']])->cascade()->forHumans()}}
                @elseif($status['id'] != \App\Models\Order::STATUS_NEW)
                    0 Seconds
                @endif
            </small>
            <span class="timeline-circle {{$status['id'] == $order->status? 'active':''}}"></span>
            <span class="d-block mt-2 align-center d-flex align-items-center align-content-center">
                {{$status['title']}}
            </span>
            {{--<br>
            @include('admin.orders._partials.statuses.'.$status['id'])--}}
        </li>
    @endforeach
</ul>
