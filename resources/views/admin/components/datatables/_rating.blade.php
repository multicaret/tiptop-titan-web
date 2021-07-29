<div>
    @if($rating > 0)
        <div class="rating">
            <i class="text-{{$rating == 1?'danger':'warning'}} {{$rating >= 1 ? "fas" : "far"}} fa-star"></i>
            <i class="text-{{$rating == 1?'danger':'warning'}} {{$rating >= 2 ? "fas" : "far"}} fa-star"></i>
            <i class="text-{{$rating == 1?'danger':'warning'}} {{$rating >= 3 ? "fas" : "far"}} fa-star"></i>
            <i class="text-{{$rating == 1?'danger':'warning'}} {{$rating >= 4 ? "fas" : "far"}} fa-star"></i>
            <i class="text-{{$rating == 1?'danger':'warning'}} {{$rating >= 5 ? "fas" : "far"}} fa-star"></i>
            <strong>{{sprintf('%0.1f', $rating)}}</strong>
        </div>
    @else
        <i>Not Rated</i>
    @endif
</div>
