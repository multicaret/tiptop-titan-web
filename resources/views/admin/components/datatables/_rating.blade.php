<div>
    <div class="rating">
        <i class="text-warning {{$rating >= 0.5 ? "fas" : "far"}} fa-star"></i>
        <i class="text-warning {{$rating >= 1.5 ? "fas" : "far"}} fa-star"></i>
        <i class="text-warning {{$rating >= 2.5 ? "fas" : "far"}} fa-star"></i>
        <i class="text-warning {{$rating >= 3.5 ? "fas" : "far"}} fa-star"></i>
        <i class="text-warning {{$rating >= 4.5 ? "fas" : "far"}} fa-star"></i>
        <strong>{{sprintf('%0.1f', $rating)}}</strong>
    </div>
</div>
