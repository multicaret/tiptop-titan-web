<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Chain;
use App\Models\Product;
use App\Models\Taxonomy;
use Illuminate\Http\RedirectResponse;
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
        return view('admin.products.form', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate($this->validationRules());
        $product = new Product();

        $this->storeSaveLogic($request, $product);

        return redirect()
            ->route('admin.products.index', ['type' => $request->type])
            ->with('message', [
                'type' => 'Success',
                'text' => 'Stored successfully',
            ]);
    }

    public function edit(Request $request, Product $product) {
        $data = $this->essentialData($request);
        $data['product'] = $product;
        return view('admin.products.form', $data);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $request->validate($this->validationRules());

        dd("update", $request->all());

        $this->storeSaveLogic($request, $product);

        return redirect()
            ->route('admin.products.index', ['type' => $request->type])
            ->with('message', [
                'type' => 'Success',
                'text' => 'Updated successfully',
            ]);
    }

    public function destroy(Request $request) {
        dd("store", $request->all());
    }

    private function essentialData(Request $request): array
    {
        $data['product'] = new Product();
        $tableName = $data['product']->getTable();
        $data['translatedInputs'] = Controller::getTranslatedAttributesFromTable($tableName, Product::getDroppedColumns());
        $data['allInputs'] = Controller::getAttributesFromTable($tableName, Product::getDroppedColumns());

        $data['typeName'] = $request->input('type');
        $getIdTitle = function ($item) {
            return ['id' => $item->id, 'title' => $item->title];
        };
        $data['chains'] = Chain::whereType(Chain::TYPE_GROCERY_CHAIN)->get()->map($getIdTitle)->all();
        $data['branches'] = Branch::whereType(Branch::TYPE_GROCERY_BRANCH)->get()->map($getIdTitle)->all();
        $data['units'] = Taxonomy::unitCategories()->get()->map($getIdTitle)->all();
        $data['categories'] = Taxonomy::groceryCategories()->get()->map($getIdTitle)->all();

        return $data;
    }

    private function validationRules(): array
    {
        $defaultLocale = localization()->getDefaultLocale();

        return [
            "{$defaultLocale}.title" => 'required',
            "chain_id" => 'required',
            "branch_id" => 'required',
            "categories" => 'required',
            "unit_id" => 'required',
            "price" => 'required',
            "type" => 'required',
        ];
    }

    private function storeSaveLogic(Request $request, Product $product): void
    {
        \DB::beginTransaction();
        $ids = \Arr::pluck(json_decode($request->input('categories'), true), 'id');
        $product->creator_id = $product->editor_id = auth()->id();
        $product->category_id = count($ids) > 0 ? $ids[0] : null;
        $product->chain_id = optional(json_decode($request->input('chain_id')))->id;
        $product->branch_id = optional(json_decode($request->input('branch_id')))->id;
        $product->unit_id = optional(json_decode($request->input('unit_id')))->id;
        $product->price = $request->input('price');
        $product->price_discount_amount = $request->input('price_discount_amount');
        $product->available_quantity = $request->input('available_quantity');
        $product->minimum_orderable_quantity = $request->input('minimum_orderable_quantity');
        $product->price_discount_began_at = $request->input('price_discount_began_at');
        $product->price_discount_finished_at = $request->input('price_discount_finished_at');
        $product->custom_banner_began_at = $request->input('custom_banner_began_at');
        $product->custom_banner_ended_at = $request->input('custom_banner_ended_at');
        $product->is_storage_tracking_enabled = $request->input('is_storage_tracking_enabled') === 'on';
        $product->price_discount_by_percentage = $request->input('price_discount_by_percentage') === 'on';
        $product->status = $request->input('status');
        $product->type = Product::getCorrectType($request->input('type'));
        $product->save();

        $product->categories()->sync($ids);

        foreach (localization()->getSupportedLocales() as $key => $value) {
            if ($request->input($key.'.title')) {
                $product->translateOrNew($key)->title = $request->input($key.'.title');
                $product->translateOrNew($key)->description = $request->input($key.'.description');
                $product->translateOrNew($key)->excerpt = $request->input($key.'.excerpt');
                $product->translateOrNew($key)->notes = $request->input($key.'.notes');
                $product->translateOrNew($key)->custom_banner_text = $request->input($key.'.custom_banner_text');
                $product->translateOrNew($key)->unit_text = $request->input($key.'.unit_text');
            }
        }
        $product->push();
        \DB::commit();

        $this->handleSubmittedSingleMedia('cover', $request, $product);
        $this->handleSubmittedMedia($request, 'gallery', $product, 'gallery');
    }
}
