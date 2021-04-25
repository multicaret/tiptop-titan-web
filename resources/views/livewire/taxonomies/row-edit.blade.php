<tr>
    <td style="width:10px">
        {{$taxonomy->id}}
    </td>
    {{--<td>
        <img src="{{$taxonomy->cover}}" width="100px" class="img-fluid-fit-cover">
    </td>--}}
    <td>
        <div class="row">
            @foreach(localization()->getSupportedLocales() as $key => $locale)
                <div class="form-group col-4">
                    <label>Title {{$locale->native()}}</label>
                    <input type="text" title="price" class="form-control" placeholder="Title {{$locale->native()}}"
                           wire:model.lazy="title{{ucfirst($key)}}">
                </div>
            @endforeach
        </div>
    </td>
    <td>
        <div class="form-group">
            <input type="text" title="price" class="form-control" placeholder="Order column"
                   wire:model.lazy="taxonomy.order_column">
        </div>
    </td>
    <td>{{$taxonomy->status_name}}</td>
    <td>
        <a target="_blank" class="btn btn-outline-success btn-sm d-inline-block mb-1" href="{{route('admin.taxonomies.edit',
                            ['type'=> \App\Models\Taxonomy::getTypesArray()[\App\Models\Taxonomy::TYPE_MENU_CATEGORY],
                            'branch_id' => $taxonomy->branch_id,
                            'chain_id' => $taxonomy->chain_id,
                            $taxonomy->uuid])}}">
            <i class="far fa-edit"></i>
        </a>
    </td>
</tr>
