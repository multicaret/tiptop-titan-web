<div class="row">
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

                            <div class="form-group">
                                <h4>Is based on ingredients?</h4>
                                <div class="row">
                                    <div class="col-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="is-based-on-ingredients"
                                                   value="0"
                                                   wire:model="option.is_based_on_ingredients">
                                            <label class="form-check-label" for="is-based-on-ingredients">
                                                Yes
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                   id="is-not-based-on-ingredients"
                                                   value="1"
                                                   wire:model="option.is_based_on_ingredients"
                                            >
                                            <label class="form-check-label" for="is-not-based-on-ingredients">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                @if(!$option->is_based_on_ingredients)
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

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="form-label">Min number of selection</label>
                                    <input class="form-control" type="number" min="0"
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
                        </form>

                        <hr>
                        @if($option->is_based_on_ingredients)
                            <div class="form-group">
                                <label>Ingredients</label>

                                <h4>Start</h4>
                                <x-input.selectmultiple wire:model="selectedIngredients"
                                                        prettyname="ingredients"
                                                        :options="$this->ingredientCategories->first()->ingredientsOfCategory->pluck('title', 'id')->toArray()"
                                                        :selected="3"/>
                                <h4>End</h4>

                                <div>
                                    Selected IDs: {{ implode(', ',$selectedIngredients) }}
                                </div>
                            </div>
                        @else
                            <h6>Selections:</h6>
                            <div>
                                @forelse($option->selections as $optionSelection)
                                    <livewire:product-option-selection :selection="$optionSelection"
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
