<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Brand;
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

    public function __construct()
    {

        $productType = request('type');
        $this->middleware('permission:'.$productType.'.permissions.index', ['only' => ['index', 'store']]);
        $this->middleware('permission:'.$productType.'.permissions.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:'.$productType.'.permissions.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:'.$productType.'.permissions.destroy', ['only' => ['destroy']]);
    }


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
                'data' => 'image',
                'title' => 'Image',
                'width' => '150',
                'searchable' => false,
                'bSortable' => false,
            ],
            [
                'data' => 'uuid',
                'name' => 'uuid',
                'title' => 'UUID',
                'orderable' => false,
                'searchable' => true,
                'visible' => false,
            ],
            [
                'data' => 'title',
                'name' => 'translations.title',
                'title' => 'Title',
                'orderable' => false,
                'searchable' => true
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
                'data' => 'parent_Category',
                'name' => 'parent_Category',
                'title' => 'Category',
                'searchable' => false,
                'bSortable' => false,
                'width' => '70',
            ],
            [
                'data' => 'child_category',
                'name' => 'child_category',
                'title' => 'Child Category',
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
        if (count($data['units'])) {
            $product->unit_id = $data['units'][0]['id'];
        }
        $product->load('unit');

        return view('admin.products.form', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->prepareForValidation($request);
        $request->validate($this->validationRules($request));
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
            ->route('admin.products.index', [
                'type' => $request->type,
                'only-for-chains' => $request->has('only-for-chains'),
            ])
            ->with('message', [
                'type' => 'Success',
                'text' => 'Stored successfully',
            ]);
    }

    public function edit(Request $request, Product $product)
    {
        $data = $this->essentialData($request, $product);
        $product->load([
            'chain',
            'branch',
            'category',
            'unit',
            'searchTags',
            'brand',
        ]);
        if ($product->is_grocery) {
            $product->load('categories');
        }


        return view('admin.products.form', $data);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->prepareForValidation($request);

        $request->validate($this->validationRules($request));

        try {
            $this->storeSaveLogic($request, $product);
        } catch (Exception $e) {
            return back()->with('message', [
                'type' => 'Error',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('admin.products.index', [
                'type' => $request->type,
                'only-for-chains' => $request->has('only-for-chains'),
            ])
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
        cache()->tags('products')->flush();

        return back()->with('message', [
            'type' => 'Success',
            'text' => 'Successfully Deleted',
        ]);
    }

    private function essentialData(Request $request, $product): array
    {
        $isCreate = is_null($product->id);
        $data['product'] = $product;
        $tableName = $data['product']->getTable();
        $droppedColumns = array_merge(Product::getDroppedColumns(), $this->getDroppedColumnsByType());
        $data['translatedInputs'] = Controller::getTranslatedAttributesFromTable($tableName, $droppedColumns);
        $data['allInputs'] = Controller::getAttributesFromTable($tableName, $droppedColumns);

        $data['typeName'] = $request->input('type');
        $getIdTitle = function ($item) {
            return ['id' => $item->id, 'title' => $item->title];
        };
        $data['units'] = Taxonomy::unitCategories()->get()->map($getIdTitle)->all();
        $data['brands'] = Brand::where('status', Brand::STATUS_ACTIVE)->get();
        if (Product::isGrocery()) {
            $data['chains'] = Chain::groceries()->get()->map($getIdTitle)->all();
            $groceryBranches = Branch::groceries();
            $data['branches'] = $groceryBranches->get()->map($getIdTitle)->all();
            if ($groceryBranches->count()) {
                $data['categories'] = Taxonomy::groceryCategories()->whereNotNull('parent_id')->get()->map($getIdTitle)->all();
            } else {
                $data['categories'] = [];
            }
        } else {
            $chains = Chain::foods()->get();
            $data['chains'] = $chains->map($getIdTitle)->all();

            if ( ! $isCreate) {
                $branches = Branch::whereChainId($product->chain_id)->foods()->get();
            } else {
                $branches = Branch::whereChainId($chains->first()->id)->foods()->get();
            }
            $data['branches'] = $branches->map($getIdTitle)->all();

            if ($request->has('branch_id')) {
                $data['categories'] = Branch::find($request->input('branch_id'))->menuCategories;
            } elseif ( ! $isCreate) {
                if ($product->branch) {
                    $data['categories'] = Branch::find($product->branch->id)->menuCategories()->get();
                } else {
                    $data['categories'] = Taxonomy::menuCategories()
                                                  ->whereNull('branch_id')
                                                  ->where('chain_id', $product->chain->id)
                                                  ->get();
                }
            }

            if ( ! $isCreate) {
                $menuCategories = $data['categories']->pluck('id')->toArray();
                if (count($menuCategories) && ! in_array($product->category_id, $menuCategories)) {
                    $product->category_id = $menuCategories[0];
                }
            }
        }

        $data['searchTags'] = Taxonomy::searchTags()->get();
        if ( ! $isCreate) {
            $data['selectedStatus'] = Product::getAllStatusesRich()[$product->status];
        } else {
            $data['selectedStatus'] = Product::getAllStatusesRich()[Product::STATUS_ACTIVE];
        }

        return $data;
    }

    private function validationRules($request): array
    {
        $defaultLocale = localization()->getDefaultLocale();
        $rules = [];
        if ($request->type == Product::getCorrectChannelName(Product::CHANNEL_GROCERY_OBJECT, false)) {
            $rules['categories'] = 'required';
        }
        if ($request->type == Product::getCorrectChannelName(Product::CHANNEL_FOOD_OBJECT, false)) {
            $rules['category'] = 'required';
        }
        if ($request->has('is_enable_to_store_date')) {
            $rules['price_discount_began_at'] = 'required|date';
            $rules['price_discount_finished_at'] = 'required|date|after:price_discount_began_at';
        }

        $rules["$defaultLocale.title"] = 'required';
        $rules['price'] = 'required';
        $rules['type'] = 'required';

        return $rules;
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

        $brand = json_decode($request->input('brand_id'));
        $brand_id = null;
        if ( ! empty($brand) && isset($brand[0])) {
            $brand_id = $brand[0]->id;
        }
        $product->unit_id = optional(json_decode($request->input('unit_id')))->id;
        $product->brand_id = $brand_id;
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
            $product->category_id = json_decode($request->input('category'))->id;
        }
        $product->save();


        $isGrocery ? $product->categories()->sync($ids) : null;
        $searchTagsIds = json_decode($request->input('search_tags'));
        $product->searchTags()->sync($searchTagsIds);

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

        cache()->tags('products')->flush();

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
        $request->merge([
            'brand_id' => $request->brand_id === 'null' ? null : $request->brand_id
        ]);
    }
}
