<?php


namespace App\Exports;


use App\Exports\Worksheets\MenuCategoriesExportForImporting;
use App\Exports\Worksheets\OptionSelectionsExportForImporting;
use App\Exports\Worksheets\ProductOptionsExportForImporting;
use App\Exports\Worksheets\ProductsExportForImporting;
use App\Models\Branch;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BranchProductsExport implements WithMultipleSheets
{
    use Exportable;

    private Branch $branch;

    /**
     * BranchProductsExport constructor.
     */
    public function __construct(Branch $branch)
    {
        $this->branch = $branch;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {

        $productIds = $this->branch->products()->pluck('id')->toArray();

        return [
            new MenuCategoriesExportForImporting($this->branch),
            new ProductsExportForImporting($this->branch),
            new ProductOptionsExportForImporting($productIds),
            new OptionSelectionsExportForImporting($productIds),
        ];
    }

}
