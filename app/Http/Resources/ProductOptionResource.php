<?php

namespace App\Http\Resources;

use App\Models\ProductOption;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ProductOption */
class ProductOptionResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'isBasedOnIngredients' => $this->is_based_on_ingredients,
            'isRequired' => $this->is_required,
            'type' => $this->type == 1 ? 'including' : 'excluding',
            'title' => $this->title,
            'maxNumberOfSelection' => $this->max_number_of_selection ?: 0,
            'minNumberOfSelection' => $this->min_number_of_selection ?: 0,
            'inputType' => ProductOption::inputTypesArray()[$this->input_type],
            'selectionType' => $this->selection_type == ProductOption::SELECTION_TYPE_SINGLE_VALUE ? 'single' : 'multiple',
            'selections' => ProductOptionSelectionResource::collection($this->selections),
            'ingredients' => TaxonomyMiniResource::collection($this->ingredients),
        ];
    }
}
