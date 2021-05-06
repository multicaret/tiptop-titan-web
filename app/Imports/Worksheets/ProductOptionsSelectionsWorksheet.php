<?php


namespace App\Imports\Worksheets;


use App\Imports\ProductsImporter;
use App\Imports\WorksheetImport;
use App\Models\ProductOptionIngredient;
use App\Models\ProductOptionSelection;
use Maatwebsite\Excel\Row;

class ProductOptionsSelectionsWorksheet extends WorksheetImport
{
    private ProductsImporter $productsImporter;


    public function __construct(ProductsImporter $productsImporter)
    {
        $this->productsImporter = $productsImporter;
    }

    public function onRow(Row $row)
    {
        $productOptionSelection = null;
        $currentRowNumber = $this->getRowNumber();
        $rawData = ProductsImporter::getRowRawDataWithTranslations($row->toArray());
        $rawData['product_id'] = $this->productsImporter->getProductId($rawData);
        $rawData['product_option_id'] = $this->getProductOptionId($rawData);
        unset($rawData['excel_id']);
        unset($rawData['id']);
        if ( ! is_null($rawData['product_option_id'])) {
            if ( ! is_null($rawData['ingredient_id'])) {
                try {
                    $productOptionSelection = ProductOptionIngredient::create([
                        'product_option_id' => $rawData['product_option_id'],
                        'ingredient_id' => $rawData['ingredient_id'],
                        'price' => $rawData['price']
                    ]);
                } catch (\Exception $e) {
                    dd('@ProductOptionIngredient', $e->getMessage(), $rawData);
                }
            }

            if ( ! is_null($rawData['product_id']) && is_null($rawData['ingredient_id'])) {
                unset($rawData['ingredient_id']);
                try {
                    $productOptionSelection = ProductOptionSelection::create($rawData);
                } catch (\Exception $e) {
                    dd('@ProductOptionSelection', $e->getMessage(), $rawData);
                }
            }
        }

        return $productOptionSelection;
    }

    private function getProductOptionId(?array $rawData)
    {
        $productsOptionsIds = $this->productsImporter->getProductsOptionsIds();
        if ($productsOptionsIds->has($rawData['product_id'])) {
            if (isset($productsOptionsIds->get($rawData['product_id'])[$rawData['product_option_id']])) {
                return $productsOptionsIds->get($rawData['product_id'])[$rawData['product_option_id']];
            }
        }


        return null;
    }
}
