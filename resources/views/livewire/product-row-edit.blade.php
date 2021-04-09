<tr>
    <td style="width:10px">
        {{$product->id}}
    </td>
    <td>
        <img src="{{$product->cover}}" width="100px" class="img-fluid-fit-cover">
    </td>
    <td>
        @foreach(localization()->getSupportedLocales() as $key => $locale)
            <div class="form-group">
                <label>Title {{$locale->native()}}</label>
                <input type="text" title="price" class="form-control" placeholder="{{$locale->native()}}"
                       wire:model.lazy="title{{ucfirst($key)}}">
            </div>
        @endforeach
    </td>
    <td>
        {{ implode(',',$product->categories->pluck('title')->toArray()) }}
    </td>
    <td>
        <div class="form-group">
            <input type="text" title="price" class="form-control" placeholder="Price"
                   wire:model.lazy="product.price">
            @error('product.price')
            <span class="text-danger">Please fix this field</span>
            @enderror
        </div>

        <hr>
        <span class="text-muted">Price Before:</span><br>
        <del>{{ $product->price_formatted }}</del>
        <br>
        <span class="text-muted">Price After:</span><br>
        @if($product->discounted_price != 0)
            <b>{{ $product->discounted_price_formatted }}</b>
        @else
            <b class="text-primary">FREE</b>
        @endif
    </td>
    <td>
        <div class="form-group">
            <input type="text" title="price" class="form-control" placeholder="Discount amount"
                   wire:model.lazy="product.price_discount_amount">
        </div>
        <div class="form-group">
            <div class="form-check">
                <input class="form-check-input" type="radio" id="discount-fixed"
                       value="0"
                       wire:model="product.price_discount_by_percentage">
                <label class="form-check-label" for="discount-fixed">
                    Fixed
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="discount-percentage"
                       value="1"
                       wire:model="product.price_discount_by_percentage"
                >
                <label class="form-check-label" for="discount-percentage">
                    Percentage %
                </label>
            </div>
            {{--<select class="form-control" wire:model="product.price_discount_by_percentage">
                <option value="false" {{ !$product->price_discount_by_percentage? 'selected':''}}>
                    Fixed
                </option>
                <option value="true" {{ $product->price_discount_by_percentage? 'selected':''}}>
                    Percentage %
                </option>
            </select>--}}
        </div>
        <span class="text-muted">
            Discount Amount:
        </span>
        <br>
        {{ $product->discount_amount_calculated_formatted }}
    </td>
    <td>
        <div class="form-group">
            <input type="text" title="price" class="form-control" placeholder="Order column"
                   wire:model.lazy="product.order_column">
        </div>
    </td>
    <td>{{$product->status_name}}</td>
    <td>
        <a target="_blank" class="btn btn-outline-success btn-sm d-inline-block mb-1" href="{{route('admin.products.edit',
                            ['type'=> request()->type,
                            'branch_id' => $product->branch_id,
                            'chain_id' => $product->chain_id,
                            $product->uuid])}}">
            <i class="far fa-edit"></i>
        </a>
        @if($product->type == \App\Models\Product::CHANNEL_FOOD_OBJECT)
            <a class="btn btn-outline-info btn-sm d-inline-block mb-1"
               href="{{route('admin.products.options',$product)}}" target="_blank">
                <i class="fas fa-cog"></i> Options
            </a>
        @endif
    </td>
</tr>
