<div>
    <div class="card">
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
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody {{--wire:poll.1s--}}>
                @if(\App\Models\Branch::find($branchId)->type == \App\Models\Branch::CHANNEL_FOOD_OBJECT)
                    <select wire:model="searchByCategory" class="form-control">
                        <option value="all">All</option>
                        @foreach(\App\Models\Taxonomy::menuCategories()->with('translations')->get() as $category)
                            <option value="{{ $category->id }}">{{ $category->title }}</option>
                        @endforeach
                    </select>
                    <br>
                @endif
                @if($products)

                    @forelse($products as  $product)
                        {{$product->id}}
                        <livewire:product-row-edit :product="$product" :key="time().$product->id">

                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <h4>
                                            No items found!
                                        </h4>
                                        <p>
                                            <button
                                                class="btn btn-link btn-outline-primary" {{--wire:click="resetFilters"--}}>
                                                Reset
                                                filters?
                                            </button>
                                        </p>
                                    </td>
                                </tr>
                    @endforelse
                @endif
                </tbody>
            </table>
            <div class="row">
                <div class="col-12 text-center">
                    {{--                    {{$products->links()}}--}}
                </div>
            </div>
        </div>
    </div>
</div>
