<?php

namespace App\Exports\Worksheets;

use App\Exports\WorkSheetExport;
use App\Models\Branch;
use App\Models\Taxonomy;

class MenuCategoriesExportForImporting extends WorkSheetExport
{
    protected Branch $branch;

    public function __construct(Branch $branch)
    {
        $this->branch = $branch;
    }

    public function collection()
    {
        return Taxonomy::menuCategories()
                       ->where('branch_id', $this->branch->id)
                       ->get();
    }

    public function map($taxonomy): array
    {
        return [
            $taxonomy->excel_id,
            $taxonomy->id,
            ...$taxonomy->translations->pluck('title')->toArray(),
        ];
    }

    public function headings(): array
    {
        return [
            [
                'Excel ID',
                'Category ID',
                'Arabic title',
                'English title',
                'Kurdish title',
            ],
            [
                'int',
                'int',
                'string',
                'string',
                'string',
            ],
            [
                'excel_id',
                'id',
                'title_ar',
                'title_en',
                'title_ku',
            ],
        ];
    }


    public function title(): string
    {
        return 'menu_categories';
    }
}
