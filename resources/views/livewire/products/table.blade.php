<div>
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
                    <th>Title</th>
                    <th class="width-85">Category</th>
                    <th class="width-85">Price</th>
                    <th class="width-85">Price Discount</th>
                    <th class="width-85">Order</th>
                    <th class="width-85">Status</th>
                    <th class="width-85">Actions</th>
                </tr>
                </thead>
                <tbody {{--wire:poll.1s--}}>
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
                                    {{--<p>
                                        <button
                                            class="btn btn-link btn-outline-primary" --}}{{--wire:click="resetFilters"--}}{{-->
                                            Reset
                                            filters?
                                        </button>
                                    </p>--}}
                                </td>
                            </tr>
                @endforelse
                </tbody>
            </table>
            @if($branch->is_grocery)
                <div class="row">
                    <div class="col-12 text-center">
                        {{$products->links()}}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
