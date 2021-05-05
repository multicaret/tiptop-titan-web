<?php


namespace App\Imports\Worksheets;


use App\Imports\ProductsImporter;
use App\Imports\WorksheetImport;
use App\Models\Branch;
use App\Models\Chain;
use App\Models\Product;
use Maatwebsite\Excel\Row;

class ProductWorksheet extends WorksheetImport
{
    protected Branch $branch;
    protected Chain $chain;
    private ProductsImporter $productsImporter;

    public function __construct(Chain $chain,Branch $branch, ProductsImporter $productsImporter)
    {
        $this->chain = $chain;
        $this->branch = $branch;
        $this->productsImporter = $productsImporter;
    }

    public function onRow(Row $row)
    {
        $currentRowNumber = $this->getRowNumber();
        $rawData = ProductsImporter::getRowRawDataWithTranslations($row->toArray());
        $categoryIds = $this->workOnCategoryIds($rawData);
        $this->updateBooleanAttributes(new Product(), $rawData);
        $this->updateTypeAttribute($rawData);
        $this->updateStatusAttribute($rawData);
        $rawData['category_id'] = $this->productsImporter->getMenuCategoriesIds()->get($rawData['category_id']);
        $rawData['importer_id'] = $rawData['excel_id'];
        $rawData['chain_id'] = $this->chain->id;
        $rawData['branch_id'] = $this->branch->id;
        $rawData['creator_id'] = auth()->id();
        $rawData['editor_id'] = auth()->id();
        $excelId = $rawData['excel_id'];
        unset($rawData['excel_id']);
        unset($rawData['id']);
        $product = Product::create($rawData);
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
                         ->replace(';', ',')
                         ->jsonSerialize();
        unset($rawData['category_ids']);

        return explode(',', $idsString);
    }

    private function updateBooleanAttributes(Product $product, array &$rawData): ?array
    {
        $casts = collect($product->getCasts());
        foreach ($casts->filter(fn($v) => $v === 'boolean') as $attribute => $item) {
            if (isset($rawData[$attribute])) {
                $rawData[$attribute] = \Str::lower($rawData[$attribute]) === 'yes';
            }
        }

        return $rawData;
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
}
