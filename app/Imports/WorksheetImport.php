<?php


namespace App\Imports;


use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

abstract class WorksheetImport  implements OnEachRow, WithHeadingRow, WithUpserts, WithBatchInserts, WithChunkReading
{
    use RemembersRowNumber;

    public function batchSize(): int
    {
        return 50;
    }

    public function chunkSize(): int
    {
        return 50;
    }

    public function headingRow(): int
    {
        return 3;
    }

    public function uniqueBy(): string
    {
        return 'excel_id';
    }

}
