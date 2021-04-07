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

        <hr>
        <span class="text-muted">Price Before:</span><br>
        <del>{{ \App\Models\Currency::format($product->price) }}</del>
        <br>
        <span class="text-muted">Price After:</span><br>
        <b>{{ \App\Models\Currency::format($product->price - $product->discounted_price) }}</b>
    </td>
    <td>
        <div class="form-group">
            <input type="text" title="price" class="form-control" placeholder="Discount amount"
                   wire:model.lazy="product.price_discount_amount">
        </div>
        <div class="form-group">
            {{--<div class="form-check">
                <input class="form-check-input" type="radio" id="discount-fixed"
                       value="'false'"
                       wire:model="product.price_discount_by_percentage">
                <label class="form-check-label" for="discount-fixed">
                    Fixed
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="discount-percentage"
                       value="'true'"
                       wire:model="product.price_discount_by_percentage"
                >
                <label class="form-check-label" for="discount-percentage">
                    Percentage %
                </label>
            </div>--}}
            <select class="form-control" wire:model="product.price_discount_by_percentage">
                <option value="false" {{ !$product->price_discount_by_percentage? 'selected':''}}>
                    Fixed
                </option>
                <option value="true" {{ $product->price_discount_by_percentage? 'selected':''}}>
                    Percentage %
                </option>
            </select>
        </div>
        <span class="text-muted">
            Discount Amount:
        </span>
        <br>
        {{ $product->discounted_price_formatted }}
    </td>
    <td>{{$product->status_name}}</td>
    <td>
        <a target="_blank" class="btn btn-outline-success btn-sm d-inline-block mb-1" href="{{route('admin.products.edit',
                            ['type'=> request()->type,
                            'branch_id' => $product->branch_id,
                            'chain_id' => $product->chain_id,
                            $product->uuid])}}">
            Edit
        </a>
        <button class="btn btn-outline-info btn-sm d-inline-block mb-1">
            Options
        </button>
    </td>
</tr>
