<div class="row">
    <div class="col-11">
        <div id="accordionSelection{{$selection->id}}">
            <div class="card mb-2">
                <div class="card-header shadow-sm">
                    <a class="d-flex justify-content-between text-body" data-toggle="collapse" aria-expanded="true"
                       href="#accordionSelection{{$selection->id}}-1">
                    <span>
                        <span class="text-muted">
                        Selection:
                        </span>
                        {{ $selection->title }}
                    </span>
                        <div class="collapse-icon"></div>
                    </a>
                </div>

                <div id="accordionSelection{{$selection->id}}-1" class="collapse show"
                     data-parent="#accordionSelection{{$selection->id}}">
                    <div class="card-body">

                        <form>
                            <div class="form-row">
                                @foreach(localization()->getSupportedLocales() as $key => $locale)
                                    <div class="form-group col-md-4">
                                        <label class="form-label">{{$locale->native()}}</label>
                                        <input type="text" class="form-control"
                                               placeholder="Title {{$locale->name()}}"
                                               wire:model.lazy="title{{ucfirst($key)}}">
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label class="form-label">Price</label>
                                <input class="form-control" type="number" min="0" wire:model.lazy="selection.price"
                                       placeholder="Price">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-1 px-0 pb-2">
        @if(!$markedAsDeleted)
            <button class="btn btn-sm btn-outline-danger btn-block h-100" wire:click="delete">
                Delete
            </button>
        @endif
    </div>
</div>
