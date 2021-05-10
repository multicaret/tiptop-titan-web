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

    public function __construct(Branch $branch, ProductsImporter $productsImport)
    {
        $this->branch = $branch;
        $this->productsImporter = $productsImport;
    }

    public function onRow(Row $row): ?TaxonomyModel
    {
        if ($this->productsImporter->isChecking) {
            $this->productsImporter->setMenuCategoriesIds($row->toArray()['excel_id'], $row->toArray()['excel_id']);

            return null;
        }
        $chunkOffset = $this->getChunkOffset();
        $this->currentRowNumber = $this->getRowNumber();
        [$rawData, $translatedData] = ProductsImporter::getRowRawDataWithTranslations($row->toArray());

        return $this->createTaxonomy(array_merge($rawData, $translatedData));
    }


    private function createTaxonomy(array $taxonomy): TaxonomyModel
    {
        $excelId = $taxonomy['excel_id'];
        unset($taxonomy['excel_id']);
        $taxonomy['type'] = Taxonomy::TYPE_MENU_CATEGORY;
        $taxonomy['branch_id'] = $this->branch->id;
        $taxonomy['creator_id'] = auth()->id();
        $taxonomy['editor_id'] = auth()->id();
        $taxonomy['status'] = Taxonomy::STATUS_ACTIVE;
        $taxonomy['left'] = 1;
        $taxonomy['right'] = 1;
        try {
            if ( ! is_null($taxonomy['id'])) {
                $taxonomyModel = TaxonomyModel::find($taxonomy['id']);
                $taxonomyModel->update(\Arr::except($taxonomy, localization()->getSupportedLocalesKeys()));
            } else {
                $taxonomyModel = TaxonomyModel::create($taxonomy);
            }
        } catch (\Exception $e) {
            dd($e->getMessage(), $taxonomy);
        }
        $taxonomyId = $taxonomyModel->translations()->first()->taxonomy_id;
        $this->productsImporter->setMenuCategoriesIds($excelId, $taxonomyId);

        return $taxonomyModel;
    }

    public function rules(): array
    {
        return [
            'excel_id' => [
                'required',
                'integer',
                function ($attribute, $value, $onFailure) {
                    if ($this->productsImporter->getMenuCategoriesIds()->has($value)) {
                        $onFailure("The category with Excel ID: {$value} already exists");
                    }
                }
            ]
        ];
    }
}
