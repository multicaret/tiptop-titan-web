<ul class="vertical-timeline" style="height:360px;overflow-y: scroll;padding-top:15px;margin-bottom: 2.5rem;">
    @php
        $activities = $object->activity()
                             ->with('user.media')
                             ->latest()
                             ->where('differences','not like','%avg_rating%')
                             ->get();
        $classNameLowerCase = strtolower(class_basename($object));
    @endphp
    @if($activities->count())
        @foreach($activities as $activity)
            <li class="rings border-{{$activity->getCssClassesBasedOnType()}}">
                <div class="timeline-content">
                    <div class="title text-{{$activity->getCssClassesBasedOnType()}}"><i
                            class="{{$activity->getFontAwesomeClassesBasedOnType()}}"></i>
                        {{trans('activity.'.strstr($activity->type,"_$classNameLowerCase",1))}}
                    </div>
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
