<div class="btn-group">
    <button type="button" class="btn btn-{{$currentChannel['class']}} dropdown-toggle" data-toggle="dropdown"
            data-trigger="hover">
        <span class="button-text">
            {{$currentChannel['title']}}
        </span>
    </button>
    <div class="dropdown-menu">
        @foreach(get_class($item)::getAllChannelsRich() as $channel)
            <a id="btn-status-{{$item->id}}-{{$channel['id']}}" class="dropdown-item" onclick="changeItemStatus(event, {{$item->id}})"
               href="{{ route('ajax.channels.change',
                    ['itemId'=>$item->id,
                    'relatedModel'=>get_class($item),
                    'channel'=>$channel['id']]) }}">
                {{$channel['title']}}
            </a>
        @endforeach
    </div>
</div>
