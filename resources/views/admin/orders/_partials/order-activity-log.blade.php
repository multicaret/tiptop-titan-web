<ul class="order-timeline horizontal" style="height:360px;overflow-y: scroll">
    @foreach($order->activity()->latest()->get() as $activity)
        <li class="text-{{$activity->getCssClassesBasedOnType()}} border-bottom">
            <span class="timeline-circle {{true? 'active':''}}"></span>
            <span class="d-block ml-4 d-flex align-items-start align-content-start flex-column">
                {{trans('activity.'.$activity->type)}}
                <br>
                    <ul class="w-100">
                @if($activity->differences)
                            @foreach($activity->differences as $title => $value)
                                <li class="flex-row mb-2">
                        @if($title == 'status')
                                        <span class="text-muted">{{$title}}</span>&nbsp;
                                        {{trans('strings.order_status_'.$value)}}
                                    @else
                                        <span>
                                        <span class="text-muted">{{$title}}</span>
                                    <mark>{{$value}}</mark></span>
                                    @endif
                            </li>
                            @endforeach
                        @endif
                        <li class="flex-row">
                            <span class="text-muted">By</span> &nbsp;
                            <img src="{{$activity->user->avatar}}" width="20px" class="d-inline-block rounded-circle"
                                 alt="{{ $activity->user->name }}">&nbsp;
                            {{ $activity->user->name }}
                        </li>
                </ul>
            </span>
        </li>
    @endforeach
</ul>
