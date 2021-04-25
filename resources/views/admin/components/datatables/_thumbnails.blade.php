<div class="slide-thumbnail-parent">
    <img class="slide-thumbnail" src="{{$imageUrl}}"
         alt="{{$tooltip}}" style="{{$style ?? ''}}"
         @if(!is_null($tooltip)) data-toggle='tooltip' @endif
         title="{{$tooltip}}"/>
</div>
