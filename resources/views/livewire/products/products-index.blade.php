<div>
    <div class="card">
        @if($branch->type == \App\Models\Branch::CHANNEL_FOOD_OBJECT)
            <select wire:model="searchByCategoryForFood" class="form-control">
                <option value="all">All</option>
                @foreach($branch->menuCategories()->with('translations')->get() as $category)
                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                @endforeach
            </select>
            <br>
        @elseif($branch->type == \App\Models\Branch::CHANNEL_GROCERY_OBJECT)
            <select wire:model="searchByCategoryForGrocery" class="form-control mb-2">
                <option value="all">All</option>
                @foreach(\App\Models\Taxonomy::groceryCategories()->where('chain_id',$branch->chain->id)->get() as $category)
                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                @endforeach
            </select>
        @endif
        <div class="tableFixHead">
            <table class="table card-table table-striped">
                <thead class="thead-dark">
                <tr>
                    <th style="width:10px">#</th>
                    <th>Thumbnail</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Price Discount</th>
                    <th style="width: 85px;">Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody {{--wire:poll.1s--}}>
                @if($products)
                    @forelse($products as  $product)
                        <livewire:product-row-edit :product="$product" :key="time().$product->id">
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
                @endif
                </tbody>
            </table>
            <div class="row">
                @if($products)
                    <div class="col-12 text-center">
                        {{$products->links()}}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
