<div style="display: flex; column-gap: 5px; width: 250px; flex-wrap: wrap; row-gap: 5px;">
    @foreach($items as $item)
        <a class="badge text-white bg-secondary">{{$item}}</a>
    @endforeach
</div>
