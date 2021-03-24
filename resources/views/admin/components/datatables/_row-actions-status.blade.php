<div class="btn-group">
    <button type="button" class="btn btn-{{$currentStatus['class']}} dropdown-toggle" data-toggle="dropdown"
            data-trigger="hover">
        <span class="button-text">
            {{$currentStatus['title']}}
        </span>
    </button>
    <div class="dropdown-menu">
        @foreach(get_class($item)::getAllStatusesRich() as $status)
            <a id="btn-status-{{$item->id}}-{{$status['id']}}" class="dropdown-item {{$status['id'] == $item->status ? 'd-none' : ''}}" onclick="changeItemStatus(event, {{$item->id}})"
               href="{{ route('ajax.statuses.change',
                    ['itemId'=>$item->id,
                    'relatedModel'=>get_class($item),
                    'status'=>$status['id']]) }}">
                {{$status['title']}}
            </a>
        @endforeach
    </div>
</div>
