<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\BarcodeProduct
 *
 * @property int $id
 * @property int $barcode_id
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BarcodeProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BarcodeProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BarcodeProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|BarcodeProduct whereBarcodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BarcodeProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BarcodeProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BarcodeProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BarcodeProduct whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BarcodeProduct extends Pivot
{
    //
}
