<a href="{{route('admin.notifications.handle',$notification)}}"
   class="list-group-item list-group-item-action media d-flex align-items-center"
   style="{{!is_null($notification->read_at) ? 'background: rgb(24 28 33 / 5%);': ''}}">
    @if(isset($notification->data['icon']))
        <div class="fas {{$notification->data['icon']}} text-primary fa-lg"></div>
    @endif
    @isset($notification->data['body']['en'])
        <div class="media-body line-height-condenced ml-3">
            <div class="text-body">{{($notification->data['body']['en'])}}</div>
            {{--        <div class="text-light small mt-1">--}}
            {{--        </div>--}}
            <div class="text-light small mt-1">{{$notification->created_at->ago()}}</div>
        </div>
    @endisset
</a>
