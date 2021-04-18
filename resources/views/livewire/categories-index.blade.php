<div>
    <div class="card">
        <div class="tableFixHead">
            <table class="table card-table table-striped">
                <thead class="thead-dark">
                <tr>
                    <th style="width:10px">#</th>
                    <th>Title 1</th>
                    <th>Title 2</th>
                    <th>Title 3</th>
                </tr>
                </thead>
                <tbody {{--wire:poll.1s--}}>
                @if($categories)
                    @forelse($categories as  $category)
                        <tr>
                            <td>
                                {{$category->title}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
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
