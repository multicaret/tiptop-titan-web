<div class="row" wire:click="$set('search','')">
    <div class="col-11">
        <div id="accordion{{$option->id}}">
            <div class="card mb-2">
                <div class="card-header shadow-sm">
                    <a class="d-flex justify-content-between text-body" data-toggle="collapse" aria-expanded="true"
                       href="#accordion{{$option->id}}-1">
                    <span>
                        <span class="text-muted">
                        Option:
                        </span>
                        {{ $option->title }}
                    </span>
                        <div class="collapse-icon"></div>
                    </a>
                </div>

                <div id="accordion{{$option->id}}-1" class="collapse show" data-parent="#accordion{{$option->id}}">
                    <div class="card-body">
                        <form>
                            <div class="form-row">
                                @foreach(localization()->getSupportedLocales() as $key => $locale)
                                    <div class="form-group col-md-4">
                                        <label class="form-label">Title {{$locale->native()}}</label>
                                        <input type="text" class="form-control"
                                               placeholder="Title {{$locale->name()}}"
                                               wire:model.lazy="title{{ucfirst($key)}}">
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-4">
                                    <h4>Is Required?</h4>
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                       id="is-required-{{$option->id}}"
                                                       value="1"
                                                       wire:model="option.is_required">
                                                <label class="form-check-label"
                                                       for="is-required-{{$option->id}}">
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                       id="is-not-required-{{$option->id}}"
                                                       value="0"
                                                       wire:model="option.is_required"
                                                >
                                                <label class="form-check-label"
                                                       for="is-not-required-{{$option->id}}">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-4">
                                    <h4>Is based on ingredients?</h4>
                                    @if($option->ingredients()->count() == 0 && $option->selections()->count() == 0)
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                           id="is-based-on-ingredients-{{$option->id}}"
                                                           value="1"
                                                           wire:model="option.is_based_on_ingredients">
                                                    <label class="form-check-label"
                                                           for="is-based-on-ingredients-{{$option->id}}">
                                                        Yes
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                           id="is-not-based-on-ingredients-{{$option->id}}"
                                                           value="0"
                                                           wire:model="option.is_based_on_ingredients">
                                                    <label class="form-check-label"
                                                           for="is-not-based-on-ingredients-{{$option->id}}">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <em>You have to delete all selections/ingredients first</em>
                                    @endif
                                </div>


                                <div class="form-group col-md-4">
                                    <label class="form-label">Input Type</label>
                                    <select class="form-control" wire:model="option.input_type">
                                        @if($option->selection_type == \App\Models\ProductOption::SELECTION_TYPE_SINGLE_VALUE)
                                            <option value="{{\App\Models\ProductOption::INPUT_TYPE_PILL}}">
                                                Pills
                                            </option>
                                            <option value="{{\App\Models\ProductOption::INPUT_TYPE_RADIO}}">
                                                Radio
                                            </option>
                                        @endif
                                        @if($option->selection_type == \App\Models\ProductOption::SELECTION_TYPE_MULTIPLE_VALUE)
                                            <option value="{{\App\Models\ProductOption::INPUT_TYPE_PILL}}">
                                                Pills
                                            </option>
                                            <option value="{{\App\Models\ProductOption::INPUT_TYPE_RADIO}}">
                                                Radio
                                            </option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                @if($option->is_based_on_ingredients)
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Type</label>
                                        <select class="form-control" wire:model="option.type">
                                            <option value="{{\App\Models\ProductOption::TYPE_INCLUDING}}">
                                                Including
                                            </option>
                                            <option value="{{\App\Models\ProductOption::TYPE_EXCLUDING}}">
                                                Excluding
                                            </option>
                                        </select>
                                    </div>
                                @endif
                                <div class="form-group col-md-6">
                                    <label class="form-label">Selection</label>
                                    <select class="form-control" wire:model="option.selection_type">
                                        <option value="{{\App\Models\ProductOption::SELECTION_TYPE_SINGLE_VALUE}}">
                                            Single
                                        </option>
                                        <option
                                            value="{{\App\Models\ProductOption::SELECTION_TYPE_MULTIPLE_VALUE}}">
                                            Multiple
                                        </option>
                                    </select>
                                </div>
                            </div>

                            @if($option->selection_type == \App\Models\ProductOption::SELECTION_TYPE_MULTIPLE_VALUE)
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Min number of selection</label>
                                        <input class="form-control" type="number" min="1"
                                               wire:model.lazy="option.min_number_of_selection"
                                               placeholder="Min number of selection">
                                    </div>
                                    @if($option->type != \App\Models\ProductOption::TYPE_EXCLUDING)
                                        <div class="form-group col-md-6">
                                            <label class="form-label">Max number of selection</label>
                                            <input class="form-control" type="number" min="0"
                                                   wire:model.lazy="option.max_number_of_selection"
                                                   placeholder="Max number of selection">
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </form>

                        <hr>
                        @if($option->is_based_on_ingredients)
                            <h5>Ingredients</h5>
                            <div class="form-group">
                                {{-- So sorry for writing CSS here --}}
                                <style>
                                    .ingredients-dropdown-search {
                                        position: absolute;
                                        overflow-y: scroll;
                                        display: {{optional($ingredients)->count() == 0 ?'none':'block'}};
                                        margin-top: 2px;
                                        height: {{ optional($ingredients)->count() >= 4? '100': (optional($ingredients)->count() * 40) }}px;
                                        width: 100%;
                                        border: 1px solid gray;
                                        border-radius: 4px;
                                        background: white !important;
                                        z-index: 99999;
                                        padding: 5px 10px;
                                    }

                                    .search-result-item {
                                        /*d-block border-bottom*/
                                        display: block;
                                        border-bottom: 1px solid silver;
                                        padding: 5px 10px;
                                        cursor: pointer;
                                    }

                                    .search-result-item:last-child {
                                        border-bottom: none;
                                    }

                                    .select-content {
                                        border: 1px solid #e8e8e8;
                                        border-radius: 4px;
                                        padding: 5px 10px;
                                    }

                                    .select-content input {
                                        border: none !important;
                                    }

                                    .select-content input:focus {
                                        outline: none;
                                    }
                                </style>
                                <div class="position-relative w-100">
                                    <div class="select-content">
                                        @foreach($this->option->ingredients()->orderBy('pivot_created_at')->get() as $selectedIngredient)
                                            <span class="badge badge-pill badge-primary p-2 px-3 text-secondary">
                                            {{$selectedIngredient->title}}
                                                &nbsp;&nbsp;
                                            <span class="remove py-1 cursor-pointer"
                                                  wire:click="removeIngredient({{$selectedIngredient->id}})">
                                                    <i class="fas fa-times"></i>
                                            </span>
                                        </span>
                                        @endforeach
                                        <input type="text" wire:model.debounce.200ms="search"/>
                                    </div>
                                    @if(!empty($search) && !empty($ingredients))
                                        <div class="ingredients-dropdown-search">
                                            @foreach($ingredients as $ingredient)
                                                <span
                                                    wire:click="selectIngredient({{$ingredient->id}},'{{$ingredient->title}}')"
                                                    class="search-result-item">
                                                {{ $ingredient->title }}
                                            </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <h5>Selections</h5>
                            <div>
                                @forelse($option->selections as $optionSelection)
                                    <livewire:products.product-option-selection :selection="$optionSelection"
                                                                       :key="'product-option-selection-'.$optionSelection->id"/>
                                @empty
                                    Got no selections babe!
                                @endforelse

                                <button class="btn btn-sm btn-outline-primary" wire:click="addNewSelection">
                                    <i class="fas fa-plus"></i>
                                    Add New Selection
                                </button>
                            </div>
                        @endif
                        {{-- <button wire:click="clone" class="btn btn-outline-warning btn-sm">
                             <i class="fas fa-clone"></i>
                             Clone
                         </button>--}}
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
