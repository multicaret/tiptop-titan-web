<?php

namespace App\Http\Livewire;

use App\Models\Taxonomy;
use Livewire\Component;

class CategoriesIndex extends Component
{
    public $categories;
    public $branchId;

    public function render()
    {
        $this->retrieveCategories();

        return view('livewire.categories-index');
    }


    public function retrieveCategories()
    {
        $categories = Taxonomy::menuCategories()->whereBranchId($this->branchId);

        $this->categories = $categories->latest()->get();
    }
}
