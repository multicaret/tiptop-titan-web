<?php

namespace App\Imports;


use App\Imports\Worksheets\MenuCategoriesWorksheet;
use App\Imports\Worksheets\ProductOptionsSelectionsWorksheet;
use App\Imports\Worksheets\ProductOptionsWorksheet;
use App\Imports\Worksheets\ProductWorksheet;
use App\Models\Branch;
use App\Models\Chain;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as CollectionAlias;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductsImporter implements WithMultipleSheets, SkipsUnknownSheets
{
    use WithConditionalSheets;

    public const WORKSHEET_MENU_CATEGORIES = 'menu_categories';
    public const WORKSHEET_PRODUCTS = 'products';
    public const WORKSHEET_OPTIONS = 'options';
    public const WORKSHEET_SELECTIONS = 'selections';
    protected Chain $chain;
    protected Branch $branch;
    protected CollectionAlias $menuCategoriesIds;
    protected CollectionAlias $productsIds;
    protected CollectionAlias $productsOptionsIds;

    public function __construct(Chain $chain, Branch $branch)
    {
        $this->chain = $chain;
        $this->branch = $branch;
        $this->menuCategoriesIds = collect([]);
        $this->productsIds = collect([]);
        $this->productsOptionsIds = collect([]);
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

    public function getProductsOptionsIds(): CollectionAlias
    {
        return $this->productsOptionsIds;
    }

    public function setProductsOptionsIds($productId, $optionExcelId, $optionId): void
    {
        if ($this->productsOptionsIds->has($productId)) {
            $this->productsOptionsIds->get($productId)->put($optionExcelId, $optionId);
        } else {
            $this->productsOptionsIds->put($productId, collect([$optionExcelId => $optionId]));
        }
    }


    public function conditionalSheets(): array
    {
        return [
            self::WORKSHEET_MENU_CATEGORIES => new MenuCategoriesWorksheet($this->branch, $this),
            self::WORKSHEET_PRODUCTS => new ProductWorksheet($this->chain, $this->branch, $this),
            self::WORKSHEET_OPTIONS => new ProductOptionsWorksheet($this),
            self::WORKSHEET_SELECTIONS => new ProductOptionsSelectionsWorksheet($this),
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

    public function updateBooleanAttributes(Model $model, array &$rawData): void
    {
        $casts = collect($model->getCasts());
        foreach ($casts->filter(fn($v) => $v === 'boolean') as $attribute => $item) {
            if (isset($rawData[$attribute])) {
                $rawData[$attribute] = \Str::lower($rawData[$attribute]) === 'yes';
            }
        }
    }

    public function getProductId($rawData): ?int
    {
        if (isset($this->getProductsIds()[$rawData['product_id']])) {
            return $this->getProductsIds()[$rawData['product_id']];
        }

        return null;
    }
}
