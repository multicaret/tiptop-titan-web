<?php

namespace App\Imports\Worksheets;

use App\Imports\ProductsImporter;
use App\Imports\WorksheetImport;
use App\Models\Branch;
use App\Models\Taxonomy;
use App\Models\TaxonomyModel;
use Maatwebsite\Excel\Row;

class MenuCategoriesWorksheet extends WorksheetImport
{

    protected Branch $branch;
    private ProductsImporter $productsImport;

    public function __construct(Branch $branch, ProductsImporter $productsImport)
    {
        $this->branch = $branch;
        $this->productsImport = $productsImport;
    }

    public function onRow(Row $row): TaxonomyModel
    {
        $currentRowNumber = $this->getRowNumber();
        $rawData = ProductsImporter::getRowRawDataWithTranslations($row->toArray());
        return $this->createTaxonomy($rawData);
    }


    private function createTaxonomy(array $taxonomy): TaxonomyModel
    {
        $excelId = $taxonomy['excel_id'];
        unset($taxonomy['id']);
        unset($taxonomy['excel_id']);
        $taxonomy['type'] = Taxonomy::TYPE_MENU_CATEGORY;
        $taxonomy['branch_id'] = $this->branch->id;
        $taxonomy['creator_id'] = auth()->id();
        $taxonomy['editor_id'] = auth()->id();
        $taxonomy['status'] = Taxonomy::STATUS_ACTIVE;
        $taxonomy['left'] = 1;
        $taxonomy['right'] = 1;
        try {
            $taxonomyModel = TaxonomyModel::create($taxonomy);
        } catch (\Exception $e) {
            dd($e->getMessage(), $taxonomy);
        }
        $taxonomyId = $taxonomyModel->translations()->first()->taxonomy_id;
        $this->productsImport->setMenuCategoriesIds($excelId , $taxonomyId);
        return $taxonomyModel;
    }
}
