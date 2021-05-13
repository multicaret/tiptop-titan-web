<?php

namespace App\Exports\Worksheets;

use App\Exports\WorkSheetExport;
use App\Models\ProductOptionSelection;

class OptionSelectionsExportForImporting extends WorkSheetExport
{
    protected $productIds;

    public function __construct($productIds)
    {
        $this->productIds = $productIds;
    }

    public function collection()
    {
        if ($this->productIds) {
            return ProductOptionSelection::whereIn('product_id', $this->productIds)
                                         ->get();
        }

        return null;
    }

    public function map($selection): array
    {
        return [
            $selection->excel_id,
            $selection->id,
            $selection->product_id,
            $selection->product_option_id,
            ...$selection->translations->pluck('title')->toArray(),
            $selection->price,
            $selection->ingredient_id,
        ];
    }

    public function headings(): array
    {
        return [
            'Excel ID',
            'Selection ID',
            'Product ID',
            'Product Option ID',
            'Arabic title',
            'English title',
            'Kurdish title',
            'Price',
            'Ingredient ID',
        ];
    }

    public function title(): string
    {
        return 'selections';
    }
}
