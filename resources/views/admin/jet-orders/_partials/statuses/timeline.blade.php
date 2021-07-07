<ul class="order-timeline">
    @foreach($order->getAllStatuses() as $status)
        <li class="{{$status['isSelected']? 'text-primary':''}}">
            <span class="timeline-circle {{$status['id'] == $order->status? 'active':''}}"></span>
            <span class="d-block mt-2 align-center d-flex align-items-center align-content-center">
                {{$status['title']}}
            </span>
            {{--<br>
            @include('admin.orders._partials.statuses.'.$status['id'])--}}
        </li>
    @endforeach
</ul>
