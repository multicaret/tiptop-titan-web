<ul class="vertical-timeline" style="height:360px;overflow-y: scroll">
    @php
        $activities = $order->activity()->latest()->get()
    @endphp
    @if($activities->count())
        @foreach($activities as $activity)
            <li class="rings border-{{$activity->getCssClassesBasedOnType()}}">
                <span></span>
                <div class="timeline-content">
                    <div class="title text-{{$activity->getCssClassesBasedOnType()}}"><i class="fas fa-trash-alt"></i>
                        {{trans('activity.'.$activity->type)}}
                    </div>
                    @if($activity->differences)
                        <div class="{{$activity->getCssClassesBasedOnType()}}">
                            <ul>
                                @foreach($activity->differences as $title => $value)
                                    @if($foo = \App\Models\Order::getActivityLogDifference($title,$value))
                                        <li>
                                            <b class="text-muted">{{$foo['title']}}</b>&nbsp;
                                            <span>{!! $foo['value'] !!}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="{{$activity->getCssClassesBasedOnType()}}">
                        <span>Record Update date: </span>
                        &nbsp
                        <span>{{$activity->updated_at->format(config('defaults.datetime.normal_format'))}}</span>
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
    @endif

</ul>
