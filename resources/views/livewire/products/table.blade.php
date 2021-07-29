<div>
    @php
        $loadingId = 'loader-category-';
        if ($branch->type == \App\Models\Branch::CHANNEL_FOOD_OBJECT) {
            $loadingId .= $searchByCategoryForFood;
        } else {
            $loadingId .= $searchByCategoryForGrocery;
        }
    @endphp
    <x-admin.wire-loading :variable="$searchByCategoryForFood" :id="$loadingId"/>
    <div class="card">
        @if($branch->type == \App\Models\Branch::CHANNEL_FOOD_OBJECT)
            <select wire:model="searchByCategoryForFood" class="form-control">
                <option value="all">All</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                @endforeach
            </select>
            <br>
        @elseif($branch->type == \App\Models\Branch::CHANNEL_GROCERY_OBJECT)
            <select wire:model="searchByCategoryForGrocery" class="form-control mb-2">
                <option value="all">All</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                @endforeach
            </select>
        @endif
        <div class="tableFixHead">
            <table class="table card-table table-striped">
                <thead class="thead-dark">
                <tr>
                    <th style="width:10px">#</th>
                    <th class="width-85">Cover</th>
                    <th style="width: 220px;">Title</th>
                    <th style="width:100px">Category</th>
                    <th class="width-85">Price</th>
                    <th class="width-85">Discount</th>
                    <th class="width-85">Order</th>
                    <th class="width-85">Status</th>
                    <th class="width-85">Actions</th>
                </tr>
                </thead>
                <tbody {{--wire:poll.1s--}}
                       @if($branch->type == \App\Models\Branch::CHANNEL_FOOD_OBJECT)
                       id="tbody-category-{{$searchByCategoryForFood}}"
                       @else
                       id="tbody-category-{{$searchByCategoryForGrocery}}"
                    @endif
                >
                {{--                {{dd($products)}}--}}
                @forelse($products as $product)
                    <livewire:products.product-row-edit :key="'product-row-edit-'.$product->id"
                                                        :categories="$categories"
                                                        :product="$product"
                    >
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">
                                    <h4>
                                        No items found!
                                    </h4>
                                </td>
                            </tr>
                @endforelse
                </tbody>
            </table>
            @if($branch->is_grocery)
                <div class="row"
                     @if($branch->type == \App\Models\Branch::CHANNEL_FOOD_OBJECT)
                     id="pagination-wrapper-category-{{$searchByCategoryForFood}}"
                     @else
                     id="pagination-wrapper-category-{{$searchByCategoryForGrocery}}"
                    @endif>
                    <div class="col-12 d-flex justify-content-center">
                        {{$products->links()}}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
