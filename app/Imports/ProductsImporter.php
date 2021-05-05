<?php

namespace App\Imports;


use App\Imports\Worksheets\MenuCategoriesWorksheet;
use App\Imports\Worksheets\ProductOptionsSelectionsWorksheet;
use App\Imports\Worksheets\ProductOptionsWorksheet;
use App\Imports\Worksheets\ProductWorksheet;
use App\Models\Branch;
use App\Models\Chain;
use Illuminate\Support\Collection as CollectionAlias;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use phpDocumentor\Reflection\Types\Collection;

class ProductsImporter implements WithMultipleSheets, SkipsUnknownSheets
{
    use WithConditionalSheets;

    protected Chain $chain;
    protected Branch $branch;
    protected CollectionAlias $menuCategoriesIds;
    protected CollectionAlias $productsIds;

    public function __construct(Chain $chain, Branch $branch)
    {
        $this->chain = $chain;
        $this->branch = $branch;
        $this->menuCategoriesIds = collect([]);
        $this->productsIds = collect([]);
    }

    public function getMenuCategoriesIds(): CollectionAlias
    {
        return $this->menuCategoriesIds;
    }

    public function setMenuCategoriesIds($excelId, $categoryId): void
    {
        $this->menuCategoriesIds->put($excelId, $categoryId);
    }

    public function getProductsIds(): CollectionAlias
    {
        return $this->productsIds;
    }

    public function setProductsIds($excelId, $productId): void
    {
        $this->productsIds->put($excelId, $productId);
    }


    public function conditionalSheets(): array
    {
        return [
            'menu_categories' => new MenuCategoriesWorksheet($this->branch, $this),
            'products' => new ProductWorksheet($this->chain, $this->branch, $this),
            'options' => new ProductOptionsWorksheet($this),
            'selections' => new ProductOptionsSelectionsWorksheet(),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        info("Sheet {$sheetName} was On Unknown Sheet");
        // Todo: show error if this sheet not found on uploaded file
    }

    public static function getRowRawDataWithTranslations(array $row): ?array
    {
        $rowTranslations = [];
        foreach ($row as $tempKey => $item) {
            foreach (localization()->getSupportedLocalesKeys() as $localesKey) {
                if (\Str::afterLast($tempKey, '_') === $localesKey) {
                    $translatedKey = \Str::beforeLast($tempKey, '_');
                    $rowTranslations[$localesKey][$translatedKey] = $item;
                    unset($row[$tempKey]);
                    unset($rowTranslations[$tempKey]);
                }
            }
        }

        return array_merge($row, $rowTranslations);
    }
}
