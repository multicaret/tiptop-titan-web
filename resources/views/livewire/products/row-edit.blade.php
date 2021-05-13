<tr
    @if(!is_null($categoris) && !in_array($this->product->category_id,$categoris->pluck('id')->toArray()))
    class="bg-warning"
    @endif>
    <td style="width:10px">
        {{$product->id}}
    </td>
    <td>
        <img src="{{$product->cover}}" width="75px" class="img-fluid-fit-cover">
    </td>
    <td>
        <div class="row">
            @foreach(localization()->getSupportedLocales() as $key => $locale)
                <div class="form-group col-4 p-0">
                    <label>Title {{$locale->native()}}</label>
                    <input type="text" title="price" class="form-control" placeholder="{{$locale->native()}}"
                           wire:model.lazy="title{{ucfirst($key)}}">
                </div>
            @endforeach
        </div>
    </td>
    <td>
        @if($product->type == \App\Models\Product::CHANNEL_FOOD_OBJECT)
            @if(!in_array($this->product->category_id,$categoris->pluck('id')->toArray()))
                <span class="text-danger">
                <i class="fas fa-info-circle"></i>
                {{optional($product->category)->title}}
            </span>
            @else
                {{optional($product->category)->title}}
            @endif
        @else
            {{ implode(',',$product->categories->pluck('title')->toArray()) }}
        @endif

        @if($product->type == \App\Models\Branch::CHANNEL_FOOD_OBJECT)
            <select wire:model="product.category_id" class="form-control">
                @foreach($categoris as $category)
                    <option value="{{ $category->id }}"
                        {{ $product->category_id == $category->id ? 'selected':null }}
                    >
                        #{{ $category->id }} - {{ $category->title }}
                    </option>
                @endforeach
            </select>
            <br>
            {{--@elseif($product->type == \App\Models\Branch::CHANNEL_GROCERY_OBJECT)
                <select wire:model="product.category" class="form-control mb-2">
                    @foreach($categoris as $category)
                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                    @endforeach
                </select>--}}
        @endif
    </td>
    <td>
        <div class="form-group">
            <input type="text" title="price" class="form-control" placeholder="Price"
                   wire:model.lazy="product.price">
            @error('product.price')
            <span class="text-danger">Please fix this field</span>
            @enderror
        </div>

        @if($product->price_discount_amount)
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
        @endif
    </td>
    <td>
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
                    %
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
        <div class="form-group">
            <input type="text" title="price" class="form-control" placeholder="Discount amount"
                   wire:model.lazy="product.price_discount_amount">
        </div>

        @if($product->price_discount_amount)
            <span class="text-muted">
            Discount Amount:
        </span>
            <br>
            {{ $product->discount_amount_calculated_formatted }}
        @endif
    </td>
    <td>
        <div class="form-group">
            <input type="text" title="price" class="form-control" placeholder="Order column"
                   wire:model.lazy="product.order_column">
        </div>
    </td>
    <td>{{$product->status_name}}</td>
    <td>
        <a target="_blank" class="btn btn-outline-success btn-sm d-block mb-1" href="{{route('admin.products.edit',
                            ['type'=> \App\Models\Product::getCorrectChannelName($product->type),
                            'branch_id' => $product->branch_id,
                            'chain_id' => $product->chain_id,
                            $product->uuid])}}">
            <i class="far fa-edit"></i> Edit
        </a>
        @if($product->type == \App\Models\Product::CHANNEL_FOOD_OBJECT)
            <a class="btn btn-outline-info btn-sm d-block mb-1"
               href="{{route('admin.products.options',$product)}}" target="_blank">
                <i class="fas fa-cog"></i> Options
            </a>
        @endif
    </td>
</tr>
