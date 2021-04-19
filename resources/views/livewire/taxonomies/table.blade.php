<div>
    <div class="card">
        <div class="tableFixHead">
            <table class="table card-table table-striped">
                <thead class="thead-dark">
                <tr>
                    <th style="width:10px">#</th>
                    <th>Title</th>
                    <th class="width-85">Order</th>
                    <th class="width-85">Status</th>
                    <th class="width-85">Actions</th>
                </tr>
                </thead>
                <tbody {{--wire:poll.1s--}}>
                @if($categories)
                    @forelse($categories as $category)
                        <livewire:taxonomies.taxonomy-row-edit :taxonomy="$category"
                                                               :key="'taxonomy-row-edit-'.$category->id">
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <h4>
                                            No items found!
                                        </h4>
                                    </td>
                                </tr>
                    @endforelse
                @endif
                </tbody>
            </table>
            <div class="row">
                @if($categories)
                    <div class="col-12 text-center">
                        {{--                        {{$categorys->links()}}--}}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
