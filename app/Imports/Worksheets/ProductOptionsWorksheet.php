<?php


namespace App\Imports\Worksheets;


use App\Imports\productsImporter;
use App\Imports\WorksheetImport;
use App\Models\ProductOption;
use Maatwebsite\Excel\Row;

class ProductOptionsWorksheet extends WorksheetImport
{
    private ProductsImporter $productsImporter;

    public function __construct(ProductsImporter $productsImporter)
    {
        $this->productsImporter = $productsImporter;
    }

    public function onRow(Row $row)
    {
        $currentRowNumber = $this->getRowNumber();
        $rawData = ProductsImporter::getRowRawDataWithTranslations($row->toArray());
        $this->productsImporter->updateBooleanAttributes(new ProductOption(), $rawData);
        $this->updateTypeAttribute($rawData);
        $this->updateInputTypeAttribute($rawData);
        $this->updateSelectionTypeAttribute($rawData);
        $rawData['product_id'] = $this->getProductId($rawData);
        unset($rawData['excel_id']);
        unset($rawData['id']);
        try {
            $productOption = ProductOption::create($rawData);
            $optionId = $productOption->translations()->first()->product_option_id;
            $this->productsImporter->setProductsOptionsIds($rawData['product_id'], $optionId);
        } catch (\Exception $e) {
            dd($rawData, $e->getMessage(), $this->productsImporter->getProductsIds());
        }

        return $productOption;
    }

    private function updateTypeAttribute(?array &$rawData)
    {
        if (\Str::lower($rawData['type']) === 'yes') {
            $rawData['type'] = ProductOption::TYPE_INCLUDING;
        } else {
            $rawData['type'] = ProductOption::TYPE_EXCLUDING;
        }
    }

    private function updateInputTypeAttribute(?array &$rawData)
    {
        $inputType = \Str::lower($rawData['input_type']);
        switch ($inputType) {
            case 'checkbox' :
                $rawData['input_type'] = ProductOption::INPUT_TYPE_CHECKBOX;
                break;
            case 'radio' :
                $rawData['input_type'] = ProductOption::INPUT_TYPE_RADIO;
                break;
            case 'select' :
                $rawData['input_type'] = ProductOption::INPUT_TYPE_SELECT;
                break;
            case 'pill' :
            default:
                $rawData['input_type'] = ProductOption::INPUT_TYPE_PILL;
                break;
        }
    }

    private function updateSelectionTypeAttribute(?array &$rawData)
    {
        if (\Str::lower($rawData['selection_type']) === 'yes') {
            $rawData['selection_type'] = ProductOption::SELECTION_TYPE_SINGLE_VALUE;
        } else {
            $rawData['selection_type'] = ProductOption::SELECTION_TYPE_MULTIPLE_VALUE;
        }
    }

    private function getProductId($rawData): ?int
    {
        if (isset($this->productsImporter->getProductsIds()[$rawData['product_id']])) {
            return $this->productsImporter->getProductsIds()[$rawData['product_id']];
        }

        return null;
    }
}
