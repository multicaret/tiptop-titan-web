<ul class="vertical-timeline" style="height:360px;overflow-y: scroll;padding-top:15px;margin-bottom: 2.5rem;">
    @php
        $activities = $object->activity()->latest()->get();
        $classNameLowerCase = strtolower(class_basename($object));
    @endphp
    @if($activities->count())
        @foreach($activities as $activity)
            <li class="rings border-{{$activity->getCssClassesBasedOnType()}}">
                <span></span>
                <div class="timeline-content">
                    <div class="title text-{{$activity->getCssClassesBasedOnType()}}"><i
                            class="{{$activity->getFontAwesomeClassesBasedOnType()}}"></i>
                        {{trans('activity.'.strstr($activity->type,"_$classNameLowerCase",1))}}
                    </div>
{{--                    {{dd($activity)}}--}}
                    @if($activity->differences)
                        <div class="{{$activity->getCssClassesBasedOnType()}}">
                            <ul>
                                @foreach($activity->differences as $title => $value)
                                    @if($differenceItem = $object->getActivityLogDifference($title,$value))
                                        <li>
                                            <b class="text-muted">{{$differenceItem['title']}}</b>&nbsp;
                                            <span>{!! $differenceItem['value'] !!}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="{{$activity->getCssClassesBasedOnType()}}">
                        {{--                        <span>Record Update date: </span>--}}
                        {{--                        &nbsp--}}
                        {{--                        <span>{{$activity->z_at->format(config('defaults.datetime.normal_format'))}}</span>--}}
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
