<?php


namespace App\Imports\Worksheets;


use App\Imports\ProductsImporter;
use App\Imports\WorksheetImport;
use App\Models\ProductOptionIngredient;
use App\Models\ProductOptionSelection;
use Maatwebsite\Excel\Row;

class ProductOptionsSelectionsWorksheet extends WorksheetImport
{

    public function __construct(ProductsImporter $productsImporter)
    {
        $this->productsImporter = $productsImporter;
    }

    public function onRow(Row $row)
    {
        if (is_null($row->toArray()['excel_id'])) {
            return null;
        }
        if ($this->productsImporter->isChecking) {
            $tempExcelId = $row->toArray()['excel_id'];
            $this->productsImporter->setProductsOptionsSelectionsIds($tempExcelId, $tempExcelId);

            return null;
        }
        $productOptionSelection = null;
        $currentRowNumber = $this->getRowNumber();
        [$rawData, $translatedData] = ProductsImporter::getRowRawDataWithTranslations($row->toArray());
        $rawData['product_id'] = $this->productsImporter->getProductId($rawData);
        $rawData['product_option_id'] = $this->getProductOptionId($rawData);
        unset($rawData['excel_id']);
        if ( ! is_null($rawData['product_option_id'])) {
            if ( ! is_null($rawData['ingredient_id'])) {
                try {
                    if (is_null($rawData['id'])) {
                        $productOptionSelection = ProductOptionIngredient::create([
                            'product_option_id' => $rawData['product_option_id'],
                            'ingredient_id' => $rawData['ingredient_id'],
                            'price' => $rawData['price']
                        ]);
                    } else {
                        $productOptionSelection = ProductOptionIngredient::find($rawData['id']);
                        $productOptionSelection->update([
                            'product_option_id' => $rawData['product_option_id'],
                            'ingredient_id' => $rawData['ingredient_id'],
                            'price' => $rawData['price']
                        ]);
                    }
                } catch (\Exception $e) {
                    dd('@ProductOptionIngredient', $e->getMessage(), $rawData);
                }
            }

            if ( ! is_null($rawData['product_id']) && is_null($rawData['ingredient_id'])) {
                unset($rawData['ingredient_id']);
                try {
                    if (is_null($rawData['id'])) {
                        $productOptionSelection = ProductOptionSelection::create($rawData);
                    } else {
                        $productOptionSelection = ProductOptionSelection::update($rawData);
                    }
                    $localesKeys = array_flip(localization()->getSupportedLocalesKeys());
                    foreach ($localesKeys as $localeKey => $index) {
                        $productOptionSelection->translateOrNew($localeKey)->fill($translatedData[$localeKey]);
                    }
                } catch (\Exception $e) {
                    dd('@ProductOptionSelection', $e->getMessage(), $rawData);
                }
            }
        }
        $productOptionSelection->save();

        return $productOptionSelection;
    }

    private function getProductOptionId(?array $rawData)
    {
        $productsOptionsIds = $this->productsImporter->getProductsOptionsIds();
        if ($productsOptionsIds->count() > 0 && $productsOptionsIds->has((int) $rawData['product_id'])) {
            if (isset($productsOptionsIds->get((int) $rawData['product_id'])[$rawData['product_option_id']])) {
                return $productsOptionsIds->get((int) $rawData['product_id'])[$rawData['product_option_id']];
            }
        }


        return null;
    }

    public function rules(): array
    {
        return [
            'excel_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $onFailure) {
                    if ($this->productsImporter->getProductsOptionsSelectionsIds()->count() > 0 && $this->productsImporter->getProductsOptionsSelectionsIds()->has((int) $value)) {
                        $onFailure("The selection with Excel ID: {$value} already exists");
                    }
                }
            ]
        ];
    }
}
