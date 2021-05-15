<?php

namespace App\Exports\Worksheets;

use App\Exports\WorkSheetExport;
use App\Models\ProductOption;
use Illuminate\Support\Str;

class ProductOptionsExportForImporting extends WorkSheetExport
{
    protected $productIds;

    public function __construct($productIds)
    {
        $this->productIds = $productIds;
    }

    public function collection()
    {
        if ($this->productIds) {
            return ProductOption::whereIn('product_id', $this->productIds)
                                ->get();
        }

        return null;
    }

    public function map($option): array
    {
        return [
            $option->excel_id,
            $option->id,
            $option->product_id,
            ...$option->translations->pluck('title')->toArray(),
            $option->is_based_on_ingredients_text,
            $option->is_required_text,
            $option->type_text,
            $option->max_number_of_selection,
            $option->min_number_of_selection,
            $option->input_type_text,
            $option->selection_type_text,
            $option->order_column,
        ];
    }

    public function headings(): array
    {
        return [
            [
                'Excel ID',
                'Option ID',
                'Excel Product ID',
                'Arabic Title',
                'English Title',
                'Kurdish Title',
                'Is based on ingredients?',
                'Is required?',
                'Is Option Type Including?',
                'Max number of selection',
                'Min number of selection',
                'Input Type',
                'Is Single Selection?',
                'Order Column',
            ],
            [
                'int',
                'int',
                'int',
                'string',
                'string',
                'string',
                'boolean(yes/no)',
                'boolean(yes/no)',
                'string(including/excluding)',
                'int',
                'int',
                'string(pill/radio/checkbox/select)',
                'boolean(yes/no)',
                'int',
            ],
            [
                'excel_id',
                'id',
                'product_id',
                'title_ar',
                'title_en',
                'title_ku',
                'is_based_on_ingredients',
                'is_required',
                'type',
                'max_number_of_selection',
                'min_number_of_selection',
                'input_type',
                'selection_type',
                'order_column',
            ]
        ];
    }

    public function prepareRows($rows): array
    {
        $closure = function (ProductOption $productOption) {
            $productOption->excel_id = $productOption->importer_id;
            $productOption->is_based_on_ingredients_text = $productOption->is_based_on_ingredients ? 'yes' : 'no';
            $productOption->is_required_text = $productOption->is_required ? 'yes' : 'no';
            $productOption->type_text = $productOption->type == ProductOption::TYPE_INCLUDING ? 'yes' : 'no';
            $productOption->input_type_text = Str::title(ProductOption::inputTypesArray()[$productOption->type]);
            $productOption->selection_type_text = $productOption->selection_type == ProductOption::SELECTION_TYPE_SINGLE_VALUE ? 'yes' : 'no';

            return $productOption;
        };

        return array_map($closure, $rows);
    }


    public function title(): string
    {
        return 'options';
    }
}
