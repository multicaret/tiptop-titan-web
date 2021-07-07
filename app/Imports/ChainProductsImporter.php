<?php

namespace App\Imports;


use App\Imports\Worksheets\ChainProductWorksheet;
use App\Models\Chain;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ChainProductsImporter implements WithMultipleSheets/*, SkipsUnknownSheets*/
{
    use WithConditionalSheets;

    public const WORKSHEET_PRODUCTS = 'products';
    protected Chain $chain;

    public function __construct(Chain $chain)
    {
        $this->chain = $chain;
    }

    public function conditionalSheets(): array
    {
        return [
            self::WORKSHEET_PRODUCTS => new ChainProductWorksheet($this->chain),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        info("Sheet {$sheetName} was On Unknown Sheet");
        // Todo: show error if this sheet not found on uploaded file
    }

    public static function getRowRawDataWithTranslations(array $row): ?array
    {
        $rowTranslations = [];
        foreach ($row as $tempKey => $item) {
            if (empty($tempKey)) {
                unset($row[$tempKey]);
                continue;
            }
            foreach (localization()->getSupportedLocalesKeys() as $localesKey) {
                if (\Str::afterLast($tempKey, '_') === $localesKey) {
                    $translatedKey = \Str::beforeLast($tempKey, '_');
                    $rowTranslations[$localesKey][$translatedKey] = $item;
                    unset($row[$tempKey]);
                    unset($rowTranslations[$tempKey]);
                }
            }
        }

        return [$row, $rowTranslations];
    }

    public static function updateBooleanAttributes(Model $model, array &$rawData): void
    {
        $casts = collect($model->getCasts());
        foreach ($casts->filter(fn($v) => $v === 'boolean') as $attribute => $item) {
            if (isset($rawData[$attribute])) {
                $rawData[$attribute] = \Str::lower($rawData[$attribute]) === 'yes';
            }
        }
    }

}
