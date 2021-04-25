<ul class="vertical-timeline" style="height:360px;overflow-y: scroll">
    @foreach($order->activity()->latest()->get() as $activity)
        <li class="rings border-{{$activity->getCssClassesBasedOnType()}}">
            <span></span>
            <div class="timeline-content">
                <div class="title text-{{$activity->getCssClassesBasedOnType()}}"><i class="fas fa-trash-alt"></i>
                    {{trans('activity.'.$activity->type)}}
                </div>
                @if($activity->differences)
                    <div class="{{$activity->getCssClassesBasedOnType()}}">
                        @foreach($activity->differences as $title => $value)
                            @if($title == 'status')
                                <span>{{$title}}</span>&nbsp;
                                <span>{{trans('strings.order_status_'.$value)}}</span>
                            @else
                                <span>
                                    <span>{{ucfirst($title)}} : </span>
                                    &nbsp
                                    <span>{{$value}}</span>
                                </span>
                            @endif
                        @endforeach
                        {{--                        <span>Status: </span>--}}
                        {{--                        <span>Delivered</span>--}}
                    </div>
                @endif
                <div class="{{$activity->getCssClassesBasedOnType()}}">
                    <span>Updated_at: </span>
                    &nbsp
                    <span>{{$activity->updated_at}}</span>
                </div>
                <div class="edit-by">
                    <div class="person">
                        <img src="{{$activity->user->avatar}}" alt="{{$activity->user->name}}"/>
                    </div>
                    {{$activity->user->name}}
                </div>
            </div>
            <span class="date-and-time">
                <span class="time">{{$activity->created_at->format('H:i')}}</span>
                <span class="date">{{$activity->created_at->format('Y-m-d')}}</span>
            </span>
        </li>
    @endforeach

    {{--<li class="rings update">
        <div class="timeline-content">
            <span></span>
            <div class="title text-warning"><i class="fas fa-pen"></i> Updated</div>
            <div class="info">
                <span>Status: </span>
                <span>Delivered</span>
            </div>
            <div class="info">
                <span>Updated_at: </span>
                <span>2021</span>
            </div>
            <div class="edit-by">
                <span>By: </span>
                <div class="person">
                    <img src="{{$auth->avatar}}"/>
                </div>
            </div>
            <div class="managers">Super Admin</div>
        </div>
        <span class="date-and-time">
                    <span class="time">13:00</span>
                    <span class="date">18-4-2021</span>
                </span>
    </li>

    <li class="rings create">
        <div class="timeline-content">
            <span></span>
            <div class="title text-success"><i class="fas fa-check"></i> Created</div>
            <div class="info">
                <span>Status: </span>
                <span>Delivered</span>
            </div>
            <div class="info">
                <span>Updated_at: </span>
                <span>2021</span>
            </div>
            <div class="edit-by">
                <span>By: </span>
                <div class="person">
                    <img src="{{$auth->avatar}}"/>
                </div>
            </div>
            <div class="managers">Super Admin</div>
        </div>
        <span class="date-and-time">
                    <span class="time">15:00</span>
                    <span class="date">18-4-2021</span>
                </span>
    </li>

    <li class="rings note">
        <div class="timeline-content">
            <span></span>
            <div class="title text-primary"><i class="fas fa-flag"></i> Note</div>
            <div class="info">
                <span>Status: </span>
                <span>Delivered</span>
            </div>
            <div class="info">
                <span>Updated_at: </span>
                <span>2021</span>
            </div>
            <div class="edit-by">
                <span>By: </span>
                <div class="person">
                    <img src="{{$auth->avatar}}"/>
                </div>
            </div>
            <div class="managers">Super Admin</div>
        </div>
        <span class="date-and-time">
                    <span class="time">15:00</span>
                    <span class="date">18-4-2021</span>
                </span>
    </li>--}}

</ul>

{{--<ul class="order-timeline horizontal" style="height:360px;overflow-y: scroll">--}}
{{--    @foreach($order->activity()->latest()->get() as $activity)--}}
{{--        <li class="text-{{$activity->getCssClassesBasedOnType()}} border-bottom">--}}
{{--            <span class="timeline-circle {{true? 'active':''}}"></span>--}}
{{--            <span class="d-block ml-4 d-flex align-items-start align-content-start flex-column">--}}
{{--                {{trans('activity.'.$activity->type)}}--}}
{{--                <br>--}}
{{--                    <ul class="w-100">--}}
{{--                @if($activity->differences)--}}
{{--                            @foreach($activity->differences as $title => $value)--}}
{{--                                <li class="flex-row mb-2">--}}
{{--                        @if($title == 'status')--}}
{{--                                        <span class="text-muted">{{$title}}</span>&nbsp;--}}
{{--                                        {{trans('strings.order_status_'.$value)}}--}}
{{--                                    @else--}}
{{--                                        <span>--}}
{{--                                        <span class="text-muted">{{$title}}</span>--}}
{{--                                    <mark>{{$value}}</mark></span>--}}
{{--                                    @endif--}}
{{--                            </li>--}}
{{--                            @endforeach--}}
{{--                        @endif--}}
{{--                        <li class="flex-row">--}}
{{--                            <span class="text-muted">By</span> &nbsp;--}}
{{--                            <img src="{{$activity->user->avatar}}" width="20px" class="d-inline-block rounded-circle"--}}
{{--                                 alt="{{ $activity->user->name }}">&nbsp;--}}
{{--                            {{ $activity->user->name }}--}}
{{--                        </li>--}}
{{--                </ul>--}}
{{--            </span>--}}
{{--        </li>--}}
{{--    @endforeach--}}
{{--</ul>--}}
