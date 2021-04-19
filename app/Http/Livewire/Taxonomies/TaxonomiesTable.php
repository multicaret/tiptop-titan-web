<?php

namespace App\Http\Livewire\Taxonomies;

use App\Models\Taxonomy;
use Livewire\Component;

class TaxonomiesTable extends Component
{
    public $branchId;

    public function render()
    {
        $categories = Taxonomy::menuCategories()->whereBranchId($this->branchId)->orderBy('order_column')->get();

        return view('livewire.taxonomies.table', compact('categories'));
    }

}
