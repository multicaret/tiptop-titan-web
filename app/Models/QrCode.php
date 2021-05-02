<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|\Eloquent $qrCodeable
 * @method static Builder|QrCode newModelQuery()
 * @method static Builder|QrCode newQuery()
 * @method static Builder|QrCode query()
 * @method static Builder|QrCode whereBackcolor($value)
 * @method static Builder|QrCode whereCreatedAt($value)
 * @method static Builder|QrCode whereForecolor($value)
 * @method static Builder|QrCode whereId($value)
 * @method static Builder|QrCode whereIsExternalRoute($value)
 * @method static Builder|QrCode whereQrCodeableId($value)
 * @method static Builder|QrCode whereQrCodeableType($value)
 * @method static Builder|QrCode whereRoute($value)
 * @method static Builder|QrCode whereRouteParams($value)
 * @method static Builder|QrCode whereUpdatedAt($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
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
