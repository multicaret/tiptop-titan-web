<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Chain;
use App\Models\Product;
use App\Models\Taxonomy;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(Request $request) {
        $columns = [
            [
                'data' => 'id',
                'name' => 'id',
                'title' => trans('strings.id'),
                'width' => '5',
            ],
            [
                'data' => 'title',
                'name' => 'translations.title',
                'title' => trans('strings.title'),
                'width' => '150',
            ],
            [
                'data' => 'chain',
                'name' => 'chain',
                'title' => 'Chain',
                'searchable' => false,
                'bSortable' => false,
                'width' => '100',
            ],
            [
                'data' => 'branch',
                'name' => 'branch',
                'title' => 'Branch',
                'searchable' => false,
                'bSortable' => false,
                'width' => '150',
            ],
            [
                'data' => 'price',
                'name' => 'price',
                'title' => 'Price',
                'searchable' => false,
                'bSortable' => false,
                'width' => '70',
            ],
            [
                'data' => 'created_at',
                'name' => 'created_at',
                'title' => trans('strings.create_date'),
                'width' => '70',
            ],
        ];

        $typeName = $request->input('type');

        return view('admin.products.index', compact('columns', 'typeName'));
    }

    public function create(Request $request) {
        $data = $this->essentialData($request);
        $data['product'] = new Product();
        $tableName = $data['product']->getTable();
        $data['translatedInputs'] = Controller::getTranslatedAttributesFromTable($tableName, Product::getDroppedColumns());
        $data['allInputs'] = Controller::getAttributesFromTable($tableName, Product::getDroppedColumns());
        return view('admin.products.form', $data);
    }

    public function store(Request $request) {
        dd("store", $request->all());
    }

    public function update(Request $request) {
        dd("store", $request->all());
    }

    public function destroy(Request $request) {
        dd("store", $request->all());
    }

    private function essentialData(Request $request): array
    {
        $data['typeName'] = $request->input('type');
        $getIdTitle = function ($item) {
            return ['id' => $item->id, 'title' => $item->title];
        };
        $data['chains'] = Chain::whereType(Chain::TYPE_GROCERY_CHAIN)->get()->map($getIdTitle)->all();
        $data['branches'] = Branch::whereType(Branch::TYPE_GROCERY_BRANCH)->get()->map($getIdTitle)->all();
        $data['units'] = Taxonomy::postCategories()->get()->map($getIdTitle)->all();
        $data['categories'] = Taxonomy::groceryCategories()->get()->map($getIdTitle)->all();

        return $data;
    }
}
