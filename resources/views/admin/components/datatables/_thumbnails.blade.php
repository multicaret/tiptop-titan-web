@foreach($thumbnails as $thumbnail)
    <div class="slide-thumbnail-parent">
        <img class="slide-thumbnail" src="{{$thumbnail['image']}}"
             alt="thumbnail" data-toggle='tooltip' title="{{isset($thumbnail['tooltip']) ? $thumbnail['tooltip'] : 'thumbnail'}}" />
    </div>
@endforeach
