<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\QrCode
 *
 * @property int $id
 * @property string $qr_codeable_type
 * @property int $qr_codeable_id
 * @property string $route
 * @property array|null $route_params
 * @property int $is_external_route
 * @property string $forecolor
 * @property string $backcolor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $qrCodeable
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereBackcolor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereForecolor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereIsExternalRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereQrCodeableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereQrCodeableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereRouteParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QrCode extends Model
{
    protected $casts = [
        'route_params' => 'array',
    ];

    /**
     * Get the owning QR Codeable model.
     */
    public function qrCodeable()
    {
        return $this->morphTo();
    }
}
