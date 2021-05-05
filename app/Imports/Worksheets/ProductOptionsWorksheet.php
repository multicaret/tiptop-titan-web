<?php


namespace App\Imports\Worksheets;


use App\Imports\productsImporter;
use App\Imports\WorksheetImport;
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
        dd('ProductOptionsWorksheet',$this->productsImporter->getProductsIds());
    }
}
