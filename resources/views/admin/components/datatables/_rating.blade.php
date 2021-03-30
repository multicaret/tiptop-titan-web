<div>
    <div class="rating">
        <i class="{{$rating >= 0.5 ? "fas" : "far"}} fa-star"></i>
        <i class="{{$rating >= 1.5 ? "fas" : "far"}} fa-star"></i>
        <i class="{{$rating >= 2.5 ? "fas" : "far"}} fa-star"></i>
        <i class="{{$rating >= 3.5 ? "fas" : "far"}} fa-star"></i>
        <i class="{{$rating >= 4.5 ? "fas" : "far"}} fa-star"></i>
        <strong>{{sprintf('%0.1f', $rating)}}</strong>
    </div>
</div>
