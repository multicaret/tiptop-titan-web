<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
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
        $typeName = $request->input('type');
        $product = new Product();
        $tableName = $product->getTable();
        $translatedInputs = Controller::getTranslatedAttributesFromTable($tableName, Product::getDroppedColumns());
        $allInputs = Controller::getAttributesFromTable($tableName, Product::getDroppedColumns());
        return view('admin.products.form', compact('product', 'typeName', 'translatedInputs', 'allInputs'));

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
}
