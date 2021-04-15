<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\OrderAgentNote
 *
 * @property int $id
 * @property int $order_id
 * @property int $agent_id
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $agent
 * @property-read \App\Models\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAgentNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAgentNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAgentNote query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAgentNote whereAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAgentNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAgentNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAgentNote whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAgentNote whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAgentNote whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderAgentNote extends Model
{
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
