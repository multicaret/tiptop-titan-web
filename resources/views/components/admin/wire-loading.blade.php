<div wire:loading.flex class="global-loading-blockage-plane" id="{{$id??mt_rand(0,100000)}}">
    <i class="fas fa-sync fa-spin fa-2x"></i>
    <br>
    <h3>
        @if(isset($text))
        @else
            Loading...
        @endif
    </h3>
</div>
