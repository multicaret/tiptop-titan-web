<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
