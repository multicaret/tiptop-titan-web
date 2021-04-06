<div>
    <div class="card">
        <div class="tableFixHead">
            <table class="table card-table table-striped">
                <thead class="thead-dark">
                <tr>
                    <th style="width:10px">#</th>
                    <th>Thumbnail</th>
                    @foreach(localization()->getSupportedLocales() as $key => $locale)
                        <th>{{$locale->name()}} Translation</th>
                    @endforeach
                    <th>Category</th>
                    <th>Order</th>
                    <th>Price</th>
                    <th>Price Discount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody {{--wire:poll.1s--}}>
                @if($products)
                    @forelse($products as $product)
                        <livewire:product-row-edit :product="$product" :key="'product-row-'. $product->id">

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
                    {{$products->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
