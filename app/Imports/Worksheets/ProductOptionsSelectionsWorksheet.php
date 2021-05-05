<?php


namespace App\Imports\Worksheets;


use App\Imports\ProductsImporter;
use App\Imports\WorksheetImport;
use Maatwebsite\Excel\Row;

class ProductOptionsSelectionsWorksheet extends WorksheetImport
{
    private ProductsImporter $productsImport;


    public function __construct()
    {
    }
    public function onRow(Row $row)
    {
        // TODO: Implement onRow() method.
    }
}
