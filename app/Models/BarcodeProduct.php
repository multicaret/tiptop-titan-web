<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * App\Models\BarcodeProduct
 *
 * @property int $id
 * @property int $barcode_id
 * @property int $product_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|BarcodeProduct newModelQuery()
 * @method static Builder|BarcodeProduct newQuery()
 * @method static Builder|BarcodeProduct query()
 * @method static Builder|BarcodeProduct whereBarcodeId($value)
 * @method static Builder|BarcodeProduct whereCreatedAt($value)
 * @method static Builder|BarcodeProduct whereId($value)
 * @method static Builder|BarcodeProduct whereProductId($value)
 * @method static Builder|BarcodeProduct whereUpdatedAt($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class BarcodeProduct extends Pivot
{
    //
}
