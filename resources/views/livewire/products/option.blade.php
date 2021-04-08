<div class="col-4">
    <li>
        @if(!$markedAsDeleted)
            <button class="btn btn-sm btn-outline-danger" wire:click="delete">
                Delete
            </button>
        @endif
        En <br>
        <input class="form-control" wire:model.lazy="titleEn" placeholder="En title">
        Ar <br>
        <input class="form-control" wire:model.lazy="titleAr" placeholder="Ar title">
        Ku <br>
        <input class="form-control" wire:model.lazy="titleKu" placeholder="Ku title">

        Type
        <select class="form-control" wire:model="option.type">
            <option value="{{\App\Models\ProductOption::TYPE_INCLUDING}}">
                Including
            </option>
            <option value="{{\App\Models\ProductOption::TYPE_EXCLUDING}}">
                Excluding
            </option>
        </select>
        <br>


        Selection
        <select class="form-control" wire:model="option.selection_type">
            <option value="{{\App\Models\ProductOption::SELECTION_TYPE_SINGLE_VALUE}}">
                Single
            </option>
            <option value="{{\App\Models\ProductOption::SELECTION_TYPE_MULTIPLE_VALUE}}">
                Multiple
            </option>
        </select>
        <br>

        Min number of selection<br>
        <input class="form-control" wire:model.lazy="option.min_number_of_selection"
               placeholder="Min number of selection">
        <br>
        Max number of selection<br>
        <input class="form-control" wire:model.lazy="option.max_number_of_selection"
               placeholder="Max number of selection">
        {{--With selection:
        <ul>
            @forelse($option->selections as $optionSelection)
                <li>
                    {{$optionSelection->title}}
                    - {{ \App\Models\Currency::format($optionSelection->extra_price) }}
                </li>
            @empty
                <li>Got no selections babe!</li>
            @endforelse
        </ul>--}}
    </li>
</div>
