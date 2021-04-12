<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Chain;
use App\Models\Product;
use App\Models\Taxonomy;
use Arr;
use DB;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(Request $request)
    {
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

        return view('admin.products.index', compact('columns'));
    }

    public function create(Request $request)
    {
        $product = new Product();
        $product->type = Product::getCorrectChannel($request->type);
        $data = $this->essentialData($request, $product);
        info(json_encode('tfoooooo'));

        return view('admin.products.form', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->prepareForValidation($request);
        $request->validate($this->validationRules());
        $product = new Product();

//        try {
        $this->storeSaveLogic($request, $product);
        /*} catch (Exception $e) {
            return back()->with('message', [
                'type' => 'Error',
                'text' => $e->getMessage(),
            ]);
        }*/

        if ($request->has('branch_id')) {
            return redirect()
                ->route('admin.products.index', ['type' => $request->type])
                ->with('message', [
                    'type' => 'Success',
                    'text' => 'Stored successfully',
                ]);

        }

        return redirect()
            ->route('admin.products.index', ['type' => $request->type])
            ->with('message', [
                'type' => 'Success',
                'text' => 'Stored successfully',
            ]);
    }

    public function edit(Request $request, Product $product)
    {
        $product->load(['masterCategory']);
        $data = $this->essentialData($request, $product);
        $data['product'] = $product;
        $product->load(['unit']);


        return view('admin.products.form', $data);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->prepareForValidation($request);

        $request->validate($this->validationRules());

        try {
            $this->storeSaveLogic($request, $product);
        } catch (Exception $e) {
            return back()->with('message', [
                'type' => 'Error',
                'text' => $e->getMessage(),
            ]);
        }
        if ($request->has('branch_id')) {
            return redirect()
                ->route('admin.products', ['type' => $request->type])
                ->with('message', [
                    'type' => 'Success',
                    'text' => 'Updated successfully',
                ]);

        }

        return redirect()
            ->route('admin.products.index', ['type' => $request->type])
            ->with('message', [
                'type' => 'Success',
                'text' => 'Updated successfully',
            ]);
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        try {
            $product->delete();
        } catch (Exception $e) {
            return redirect()
                ->route('admin.products.index', ['type' => $request->type])
                ->with('message', [
                    'type' => 'Error',
                    'text' => $e->getMessage(),
                ]);
        }

        return back()->with('message', [
            'type' => 'Success',
            'text' => 'Successfully Deleted',
        ]);
    }

    private function essentialData(Request $request, $product): array
    {
        if (is_null($product->id)) {
            if ($product->type == Product::CHANNEL_GROCERY_OBJECT) {
                $product->chain_id = optional(Chain::groceries()->first())->id;
            } else {
                $product->chain_id = optional(Chain::foods()->first())->id;
            }
            if ($request->has('branch_id') && ! is_null($request->input('branch_id'))) {
                $product->branch_id = Branch::find($request->input('branch_id'))->id;
            } else {
                $product->branch_id = optional(Branch::whereChainId(optional($product->chain)->id)->first())->id;
            }
        }
        $data['product'] = $product;
        $tableName = $data['product']->getTable();
        $droppedColumns = array_merge(Product::getDroppedColumns(), $this->getDroppedColumnsByType());
        $data['translatedInputs'] = Controller::getTranslatedAttributesFromTable($tableName, $droppedColumns);
        $data['allInputs'] = Controller::getAttributesFromTable($tableName, $droppedColumns);

        $data['typeName'] = $request->input('type');
        $getIdTitle = function ($item) {
            return ['id' => $item->id, 'title' => $item->title];
        };
        if ($product->type == Product::CHANNEL_GROCERY_OBJECT) {
            $data['chains'] = Chain::groceries()->get()->map($getIdTitle)->all();
            $data['branches'] = Branch::groceries()->get()->map($getIdTitle)->all();
        } else {
            $product->categories->add($product->masterCategory);
            $chains = Chain::foods()->get();
            $branches = Branch::whereChainId($chains->first()->id)->foods()->get();
            $data['chains'] = $chains->map($getIdTitle)->all();
            if (is_null($product->branch_id)) {
                $product->branch = $branches->first();
            }
            $data['branches'] = $branches->map($getIdTitle)->all();
        }
        $data['units'] = Taxonomy::unitCategories()->get()->map($getIdTitle)->all();
        if (Product::isGrocery()) {
            $data['categories'] = Taxonomy::groceryCategories()->whereNotNull('parent_id')->get()->map($getIdTitle)->all();
        } else {
            /*$data['categories'] = Taxonomy::menuCategories()
                                          ->where('branch_id', optional($product->branch)->id)
                                          ->get()
                                          ->map($getIdTitle)
                                          ->all();*/
            $data['categories'] = Taxonomy::menuCategories()->get();
        }

        return $data;
    }

    private function validationRules(): array
    {
        $defaultLocale = localization()->getDefaultLocale();

        return [
            "{$defaultLocale}.title" => 'required',
//            'chain_id' => 'required',
//            'branch_id' => 'required',
            'categories' => 'required',
//            'unit_id' => 'required',
            'price' => 'required',
            'type' => 'required',
        ];
    }

    private function storeSaveLogic(Request $request, Product $product): void
    {
        DB::beginTransaction();

        $product->creator_id = $product->editor_id = auth()->id();
        if ($request->has('chain_id') && ! is_null($request->input('chain_id'))) {
            $product->chain_id = $request->input('chain_id');
        } else {
            $product->chain_id = optional(json_decode($request->input('chain')))->id;
        }

        if ($request->has('branch_id') && ! is_null($request->input('branch_id'))) {
            $product->branch_id = $request->input('branch_id');
        } else {
            $product->branch_id = optional(json_decode($request->input('branch')))->id;
        }

        $product->unit_id = optional(json_decode($request->input('unit_id')))->id;
        $product->price = $request->input('price');
        $product->price_discount_amount = $request->input('price_discount_amount');
        $product->available_quantity = $request->input('available_quantity');
        $product->minimum_orderable_quantity = $request->input('minimum_orderable_quantity');
        $product->maximum_orderable_quantity = $request->input('maximum_orderable_quantity');
        $product->price_discount_began_at = $request->input('price_discount_began_at');
        $product->price_discount_finished_at = $request->input('price_discount_finished_at');
        $product->custom_banner_began_at = $request->input('custom_banner_began_at');
        $product->custom_banner_ended_at = $request->input('custom_banner_ended_at');
        $product->is_storage_tracking_enabled = $request->input('is_storage_tracking_enabled') === 'on';
        $product->price_discount_by_percentage = $request->input('price_discount_by_percentage') === 'on';
        $product->status = $request->input('status');
        $product->type = Product::getCorrectChannel($request->input('type'));
        $isGrocery = $product->type == Product::CHANNEL_GROCERY_OBJECT;
        if ($isGrocery) {
            $ids = Arr::pluck(json_decode($request->input('categories'), true), 'id');
            $product->category_id = $ids[0];
        } else {
            $product->category_id = json_decode($request->input('categories'))->id;
        }
        $product->save();


        $isGrocery ? $product->categories()->sync($ids) : null;

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
        DB::commit();

        $this->handleSubmittedSingleMedia('cover', $request, $product);
        $this->handleSubmittedMedia($request, 'gallery', $product, 'gallery');
    }

    private function getDroppedColumnsByType(): array
    {
        if (Product::isGrocery()) {
            return [];
        } else {
            return ['unit_id', 'unit_text'];
        }
    }

    protected function prepareForValidation(Request $request)
    {
        $request->merge([
            'chain_id' => $request->chain_id === 'null' ? null : $request->chain_id
        ]);
        $request->merge([
            'branch_id' => $request->branch_id === 'null' ? null : $request->branch_id
        ]);
        $request->merge([
            'categories' => $request->categories === 'null' ? null : $request->categories
        ]);
    }
}
