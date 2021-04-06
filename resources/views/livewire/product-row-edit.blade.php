<tr {{--class="cursor-pointer" data-toggle="modal" data-target="#orderShowModal"
                            wire:click="show({{ $product->id }})"--}}>
    <td style="width:10px">
        {{$product->id}}
    </td>
    <td>
        <img src="{{$product->cover}}" width="150px" class="img-fluid-fit-cover">
    </td>
    @foreach(localization()->getSupportedLocales() as $key => $locale)
        <td>
            <div class="form-group">
                <input type="text" title="price" class="form-control" placeholder="{{$locale->native()}}"
                       wire:model.lazy="title{{ucfirst($key)}}">
            </div>
        </td>
    @endforeach
    <td>
        {{ implode(',',$product->categories->pluck('title')->toArray()) }}
    </td>
    <td>
        <div class="form-group">
            <input type="text" title="price" class="form-control" placeholder="Order column"
                   wire:model.lazy="product.order_column">
        </div>
    </td>
    <td>
        <div class="form-group">
            <input type="text" title="price" class="form-control" placeholder="Price"
                   wire:model.lazy="product.price">
        </div>
    </td>
    <td>
        <div class="form-group">
            <input type="text" title="price" class="form-control" placeholder="Discount amount"
                   wire:model.lazy="product.price_discount_amount">
        </div>
        <div class="form-group">
            <select class="form-control" wire:model="product.price_discount_by_percentage">
                <option value="false" {{ !$product->price_discount_by_percentage? 'selected':''}}>
                    Fixed
                </option>
                <option value="true" {{ $product->price_discount_by_percentage? 'selected':''}}>
                    Percentage %
                </option>
            </select>
        </div>
        <hr>
        <span class="text-muted">
            Discount Amount:
        </span>
        <br>
        {{ $product->discounted_price_formatted }}
        <br>
        <span class="text-muted">
            Price After:
        </span>
        <br>
        {{ \App\Models\Currency::format($product->price - $product->discounted_price) }}
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
