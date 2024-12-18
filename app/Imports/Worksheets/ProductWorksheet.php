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
        if (is_null($row->toArray()['excel_id'])) {
            return null;
        }
        if ($this->productsImporter->isChecking) {
            $this->productsImporter->setProductsIds($row->toArray()['excel_id'], $row->toArray()['excel_id']);

            return null;
        }
        $currentRowNumber = $this->getRowNumber();
        [$rawData, $translatedData] = ProductsImporter::getRowRawDataWithTranslations($row->toArray());
        $categoryIds = $this->workOnCategoryIds($rawData);
        $this->productsImporter->updateBooleanAttributes(new Product(), $rawData);
        $this->updateTypeAttribute($rawData);
        $this->updateStatusAttribute($rawData);
        if ($rawData['type'] === Product::CHANNEL_GROCERY_OBJECT) {
            $rawData['category_id'] = $categoryIds[0];
        } else {
            $rawData['category_id'] = $this->productsImporter->getMenuCategoriesIds()->get((int) $rawData['category_id']);
        }
        $rawData['importer_id'] = $rawData['excel_id'];
        $rawData['chain_id'] = $this->chain->id;
        $rawData['branch_id'] = $this->branch->id;
        $rawData['creator_id'] = auth()->id();
        $rawData['editor_id'] = auth()->id();
        $excelId = $rawData['excel_id'];
        unset($rawData['excel_id']);
        try {
            if (is_null($rawData['id'])) {
                $product = Product::create($rawData);
            } else {
                $product = Product::find($rawData['id']);
                $product->update($rawData);
            }
            $localesKeys = array_flip(localization()->getSupportedLocalesKeys());
            foreach ($localesKeys as $localeKey => $index) {
                $product->translateOrNew($localeKey)->fill($translatedData[$localeKey]);
            }
        } catch (\Exception $e) {
            dd($e->getMessage(), $rawData);
        }
        $product->categories()->sync($categoryIds);
        $product->save();
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
                'nullable',
                'integer',
                function ($attribute, $value, $onFailure) {
                    if ($this->productsImporter->getProductsIds()->count() > 0 && $this->productsImporter->getProductsIds()->has((int) $value)) {
                        $onFailure("The product with Excel ID: {$value} already exists");
                    }
                }
            ],
            'category_id' => 'exclude_if:excel_id,null|nullable|integer|required_if:category_ids,==,null',
            'category_ids' => 'exclude_if:excel_id,null|nullable|required_if:category_id,==,null',
            'price' => 'exclude_if:excel_id,null|required|integer',
            'price_discount_by_percentage' => [
                'exclude_if:excel_id,null', 'required', Rule::in(['yes', 'no', 'YES', 'NO', 'Yes', 'No'])
            ],
            'price_discount_began_at' => 'exclude_if:excel_id,null|nullable|date',
            'price_discount_finished_at' => 'exclude_if:excel_id,null|nullable|date',
            'type' => ['exclude_if:excel_id,null', 'required', Rule::in(['grocery', 'food'])],
            'status' => ['exclude_if:excel_id,null', 'nullable', Rule::in(['draft', 'inactive', 'active'])],
            'is_storage_tracking_enabled' => [
                'exclude_if:excel_id,null', 'required', Rule::in(['yes', 'no', 'YES', 'NO', 'Yes', 'No'])
            ],
            'available_quantity' => 'exclude_if:excel_id,null|nullable|integer',
            'minimum_orderable_quantity' => 'exclude_if:excel_id,null|nullable|integer',
            'maximum_orderable_quantity' => 'exclude_if:excel_id,null|nullable|integer',
            'width' => 'exclude_if:excel_id,null|nullable|integer',
            'height' => 'exclude_if:excel_id,null|nullable|integer',
            'depth' => 'exclude_if:excel_id,null|nullable|integer',
            'weight' => 'exclude_if:excel_id,null|nullable|integer',
            'title_ar' => 'exclude_if:excel_id,null|nullable|string|required_if:title_en,==,null|required_if:title_ku,==,null',
            'title_en' => 'exclude_if:excel_id,null|nullable|string|required_if:title_ar,==,null|required_if:title_ku,==,null',
            'title_ku' => 'exclude_if:excel_id,null|nullable|string',
            'description_ar' => 'exclude_if:excel_id,null|nullable|string',
            'description_en' => 'exclude_if:excel_id,null|nullable|string',
            'description_ku' => 'exclude_if:excel_id,null|nullable|string',
            'excerpt_ar' => 'exclude_if:excel_id,null|nullable|string',
            'excerpt_en' => 'exclude_if:excel_id,null|nullable|string',
            'excerpt_ku' => 'exclude_if:excel_id,null|nullable|string',
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
