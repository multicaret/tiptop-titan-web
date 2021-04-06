<tr {{--class="cursor-pointer" data-toggle="modal" data-target="#orderShowModal"
                            wire:click="show({{ $product->id }})"--}}>
    <td style="width:10px">
        {{$product->id}}
    </td>
    <td>
        <img src="{{$product->cover}}" width="150px" class="img-fluid-fit-cover">
    </td>
    @foreach(localization()->getSupportedLocales() as $key => $locale)
        <td>{{$product->translate($key)->title}}</td>
    @endforeach
    <td>
        {{ implode(',',$product->categories->pluck('title')->toArray()) }}
    </td>
    <td>{{$product->order_column}}</td>
    <td>
        <div class="form-group">
            <input type="text" title="price" class="form-control" placeholder="Price"
                   wire:model="product.price">
        </div>
    </td>
    <td>
        {{$product->price_discount_amount}}
        {{$product->price_discount_by_percentage?'%':'FIXED'}}
    </td>
    <td>{{$product->status_name}}</td>
    <td>
        <button class="btn btn-outline-success btn-sm d-inline-block mb-1">
            Edit
        </button>
        <button class="btn btn-outline-info btn-sm d-inline-block mb-1">
            Options
        </button>
    </td>
</tr>
