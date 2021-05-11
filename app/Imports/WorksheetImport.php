<?php


namespace App\Imports;


use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\RemembersChunkOffset;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

abstract class WorksheetImport implements OnEachRow, WithHeadingRow, WithChunkReading, WithValidation
{
    use RemembersRowNumber;
    use RemembersChunkOffset;

    public ProductsImporter $productsImporter;
    public ?int $currentRowNumber;

    public function chunkSize(): int
    {
        return 50;
    }

    public function headingRow(): int
    {
        return 3;
    }

    public function containsOnlyNull($input): bool
    {
        return empty(array_filter($input, function ($a) { return $a !== null;}));
    }

}
