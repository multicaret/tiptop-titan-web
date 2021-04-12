<ul>
    @foreach(\App\Models\Order::getAllStatuses($order->status) as $status)
        <li class="{{$status['isSelected']? 'text-primary':''}}">
            {{$status['title']}}
        </li>
    @endforeach
</ul>

<ul class="px-4 px-lg-5 pt-4 nav nav-tabs step-anchor">
    @foreach(\App\Models\Order::getAllStatuses($order->status) as $status)
    <li class="nav-item {{$status['isSelected']? 'active':''}}">
        <a href="#shop-checkout-wizard-{{$status['id']}}" class="mb-4 nav-link">
            <span class="sw-done-icon ion ion-md-checkmark"></span>
            <span class="sw-icon ion ion-ios-airplane"></span>
            <div class="text-light small">FIRST STEP</div>
            {{$status['title']}}
        </a>
    </li>
    @endforeach
</ul>

{{--appwork-v1_5_2/extra-pages/shop-checkout.html#--}}
