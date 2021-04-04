<?php

namespace App\Http\Resources;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Currency */
class CurrencyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->english_name,
            'code' => $this->code,
            'symbol' => $this->symbol,
            'rate' => (double) $this->rate,
            'decimalSeparator' => $this->decimal_separator,
            'thousandsSeparator' => $this->thousands_separator,
            'isSymbolAfter' => $this->is_symbol_after,
        ];
    }
}
