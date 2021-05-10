<?php


namespace App\Imports\Worksheets;


use App\Imports\productsImporter;
use App\Imports\WorksheetImport;
use App\Models\ProductOption;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Row;

class ProductOptionsWorksheet extends WorksheetImport
{
    public function __construct(ProductsImporter $productsImporter)
    {
        $this->productsImporter = $productsImporter;
    }

    public function onRow(Row $row)
    {
        if ($this->productsImporter->isChecking) {
            $tempExcelId = $row->toArray()['excel_id'];
            $this->productsImporter->setProductsOptionsIds($tempExcelId, $tempExcelId, $tempExcelId);

            return null;
        }
        $currentRowNumber = $this->getRowNumber();
        [$rawData, $translatedData] = ProductsImporter::getRowRawDataWithTranslations($row->toArray());
        $this->productsImporter->updateBooleanAttributes(new ProductOption(), $rawData);
        $this->updateTypeAttribute($rawData);
        $this->updateInputTypeAttribute($rawData);
        $this->updateSelectionTypeAttribute($rawData);
        $rawData['product_id'] = $this->productsImporter->getProductId($rawData);
        $excelId = $rawData['excel_id'];
        unset($rawData['excel_id']);
        try {
            $productOption = ProductOption::updateOrCreate([
                'product_id' => $rawData['product_id'], 'type' => $rawData['type']
            ], $rawData);
            $localesKeys = array_flip(localization()->getSupportedLocalesKeys());
            foreach ($localesKeys as $localeKey => $index) {
                $productOption->translateOrNew($localeKey)->fill($translatedData[$localeKey]);
            }
        } catch (\Exception $e) {
            dd($rawData, $e->getMessage(), $this->productsImporter->getProductsIds());
        }
        $productOption->save();
        $optionId = $productOption->translations()->first()->product_option_id;
        $this->productsImporter->setProductsOptionsIds($rawData['product_id'], $excelId, $optionId);

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

    public function rules(): array
    {
        return [
            'excel_id' => [
                'required',
                'integer',
                function ($attribute, $value, $onFailure) {
                    if ($this->productsImporter->getProductsOptionsIds()->has($value)) {
                        $onFailure("The product option with Excel ID: {$value} already exists");
                    }
                }
            ],
            'product_id' => 'required|integer',
            'is_based_on_ingredients' => ['nullable', Rule::in(['yes', 'no', 'YES', 'NO', 'Yes', 'No'])],
            'is_required' => ['nullable', Rule::in(['yes', 'no', 'YES', 'NO', 'Yes', 'No'])],
            'type' => ['nullable', Rule::in(['yes', 'no', 'YES', 'NO', 'Yes', 'No'])],
            'max_number_of_selection' => 'nullable|integer',
            'min_number_of_selection' => 'nullable|integer',
            'input_type' => ['nullable', Rule::in('pill', 'radio', 'checkbox', 'select')],
            'selection_type' => ['nullable', Rule::in(['yes', 'no', 'YES', 'NO', 'Yes', 'No'])],
            'order_column' => 'nullable|integer',
        ];
    }

    public function customValidationAttributes(): array
    {
        return [
            'excel_id' => 'Excel ID',
            'product_id' => 'Product ID',
            'is_based_on_ingredients' => 'Is based on ingredients',
            'is_required' => 'Is required',
            'type' => 'Type',
            'input_type' => 'Input type',
            'selection_type' => 'Selection type',
            'title_ar' => 'Arabic title',
            'title_en' => 'English title',
            'title_ku' => 'Kurdish title',
        ];
    }

}
