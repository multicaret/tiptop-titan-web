<?php


namespace App\Imports\Worksheets;


use App\Imports\ProductsImporter;
use App\Imports\WorksheetImport;
use App\Models\Branch;
use App\Models\Chain;
use App\Models\Product;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Row;

class ProductWorksheet extends WorksheetImport
{
    protected Branch $branch;
    protected Chain $chain;

    public function __construct(Chain $chain, Branch $branch, ProductsImporter $productsImporter)
    {
        $this->chain = $chain;
        $this->branch = $branch;
        $this->productsImporter = $productsImporter;
    }

    public function onRow(Row $row)
    {
        if ($this->productsImporter->isChecking) {
            $this->productsImporter->setProductsIds($row->toArray()['excel_id'], $row->toArray()['excel_id']);

            return null;
        }
        $currentRowNumber = $this->getRowNumber();
        $rawData = ProductsImporter::getRowRawDataWithTranslations($row->toArray());
        $categoryIds = $this->workOnCategoryIds($rawData);
        $this->productsImporter->updateBooleanAttributes(new Product(), $rawData);
        $this->updateTypeAttribute($rawData);
        $this->updateStatusAttribute($rawData);
        if ($rawData['type'] === Product::CHANNEL_GROCERY_OBJECT) {
            $rawData['category_id'] = $categoryIds[0];
        } else {
            $rawData['category_id'] = $this->productsImporter->getMenuCategoriesIds()->get($rawData['category_id']);
        }
        $rawData['importer_id'] = $rawData['excel_id'];
        $rawData['chain_id'] = $this->chain->id;
        $rawData['branch_id'] = $this->branch->id;
        $rawData['creator_id'] = auth()->id();
        $rawData['editor_id'] = auth()->id();
        $excelId = $rawData['excel_id'];
        unset($rawData['excel_id']);
        unset($rawData['id']);
        try {
            $product = Product::create($rawData);
        } catch (\Exception $e) {
            dd($e->getMessage(), $rawData);
        }
        $product->categories()->sync($categoryIds);
        $this->productsImporter->setProductsIds($excelId, $product->translations()->first()->product_id);

        return $product;
    }

    private function workOnCategoryIds(&$rawData)
    {
        $idsString = \Str::of($rawData['category_ids'])
                         ->replace('|', ',')
                         ->replace('،', ',')
                         ->replace('.', ',')
                         ->replace(' ', ',')
                         ->replace(PHP_EOL, ',')
                         ->replace(';', ',')
                         ->jsonSerialize();
        unset($rawData['category_ids']);

        if ( ! empty($idsString)) {
            return explode(',', $idsString);
        }

        return [];
    }

    private function updateTypeAttribute(array &$rawData)
    {
        $rawData['type'] = Product::getCorrectChannel($rawData['type'].'-product');
    }

    private function updateStatusAttribute(array &$rawData)
    {
        $callback = function ($v) use ($rawData) {
            return \Str::lower($v) === \Str::lower($rawData['status']);
        };
        $status = collect(Product::getStatusesArray())->filter($callback)->keys()->first();
        $rawData['status'] = $status ?? Product::STATUS_ACTIVE;
    }

    public function rules(): array
    {
        return [
            'excel_id' => [
                'required',
                'integer',
                function ($attribute, $value, $onFailure) {
                    if ($this->productsImporter->getProductsIds()->has($value)) {
                        $onFailure("The product with Excel ID: {$value} already exists");
                    }
                }
            ],
            'category_id' => 'nullable|integer|required_if:category_ids,==,null',
            'category_ids' => 'nullable|required_if:category_id,==,null',
            'price' => 'required|integer',
            'price_discount_by_percentage' => ['required', Rule::in(['yes', 'no', 'YES', 'NO', 'Yes', 'No'])],
            'price_discount_began_at' => 'nullable|date',
            'price_discount_finished_at' => 'nullable|date',
            'type' => ['required', Rule::in(['grocery', 'food'])],
            'status' => ['nullable', Rule::in(['draft', 'inactive', 'active'])],
            'is_storage_tracking_enabled' => ['required', Rule::in(['yes', 'no', 'YES', 'NO', 'Yes', 'No'])],
            'available_quantity' => 'nullable|integer',
            'minimum_orderable_quantity' => 'nullable|integer',
            'maximum_orderable_quantity' => 'nullable|integer',
            'width' => 'nullable|integer',
            'height' => 'nullable|integer',
            'depth' => 'nullable|integer',
            'weight' => 'nullable|integer',
            'title_ar' => 'nullable|string|required_if:title_en,==,null|required_if:title_ku,==,null',
            'title_en' => 'nullable|string|required_if:title_ar,==,null|required_if:title_ku,==,null',
            'title_ku' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ku' => 'nullable|string',
            'excerpt_ar' => 'nullable|string',
            'excerpt_en' => 'nullable|string',
            'excerpt_ku' => 'nullable|string',
        ];
    }

    public function customValidationAttributes(): array
    {
        return [
            'excel_id' => 'Excel ID',
            'price_discount_by_percentage' => 'Price discount by percentage',
            'type' => 'Type',
            'available_quantity' => 'Available quantity',
            'is_storage_tracking_enabled' => 'Is storage tracking enabled',
            'category_id' => 'Category id',
            'category_ids' => 'Category ids',
            'price_discount_began_at' => 'Price discount began at',
            'price_discount_finished_at' => 'Price discount finished at',
            'minimum_orderable_quantity' => 'Minimum orderable quantity',
            'maximum_orderable_quantity' => 'Maximum orderable quantity',
            'width' => 'Width',
            'height' => 'Height',
            'depth' => 'Depth',
            'weight' => 'Weight',
            'title_ar' => 'Arabic title',
            'title_en' => 'English title',
            'title_ku' => 'Kurdish title',
            'description_ar' => 'Arabic description',
            'description_en' => 'English description',
            'description_ku' => 'Kurdish description',
            'excerpt_ar' => 'Arabic excerpt',
            'excerpt_en' => 'English excerpt',
            'excerpt_ku' => 'Kurdish excerpt',
        ];
    }
}
