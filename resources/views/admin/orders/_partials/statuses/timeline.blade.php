<ul class="order-timeline">
    @foreach(\App\Models\Order::getAllStatuses($order->status) as $status)
        <li class="{{$status['isSelected']? 'text-primary':''}}">
            <span class="timeline-circle {{$status['isSelected']? 'active':''}}"></span>
            <span class="d-block mt-2 align-center">
                {{$status['title']}}
            </span>
        </li>
    @endforeach
</ul>
