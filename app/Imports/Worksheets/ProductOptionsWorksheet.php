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
        if (is_null($row->toArray()['excel_id'])) {
            return null;
        }
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
            if (is_null($rawData['id'])) {
                $productOption = ProductOption::create($rawData);
            } else {
                $productOption = ProductOption::find($rawData['id']);
                $productOption->update($rawData);
            }
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
                'nullable',
                'integer',
                function ($attribute, $value, $onFailure) {
                    if ($this->productsImporter->getProductsOptionsIds()->count() > 0 && $this->productsImporter->getProductsOptionsIds()->has((int)$value)) {
                        $onFailure("The product option with Excel ID: {$value} already exists");
                    }
                }
            ],
            'product_id' => 'exclude_if:excel_id,null|required|integer',
            'is_based_on_ingredients' => [
                'exclude_if:excel_id,null', 'nullable', Rule::in(['yes', 'no', 'YES', 'NO', 'Yes', 'No'])
            ],
            'is_required' => [
                'exclude_if:excel_id,null', 'nullable', Rule::in(['yes', 'no', 'YES', 'NO', 'Yes', 'No'])
            ],
            'type' => ['exclude_if:excel_id,null', 'nullable', Rule::in(['yes', 'no', 'YES', 'NO', 'Yes', 'No'])],
            'max_number_of_selection' => 'exclude_if:excel_id,null|nullable|integer',
            'min_number_of_selection' => 'exclude_if:excel_id,null|nullable|integer',
            'input_type' => ['exclude_if:excel_id,null', 'nullable', Rule::in('pill', 'radio', 'checkbox', 'select')],
            'selection_type' => [
                'exclude_if:excel_id,null', 'nullable', Rule::in(['yes', 'no', 'YES', 'NO', 'Yes', 'No'])
            ],
            'order_column' => 'exclude_if:excel_id,null|nullable|integer',
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
