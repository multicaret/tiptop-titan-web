<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\OrderAgentNote
 *
 * @property int $id
 * @property int $order_id
 * @property int $agent_id
 * @property string $message
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\User $agent
 * @property-read \App\Models\Order $order
 * @method static Builder|OrderAgentNote newModelQuery()
 * @method static Builder|OrderAgentNote newQuery()
 * @method static Builder|OrderAgentNote query()
 * @method static Builder|OrderAgentNote whereAgentId($value)
 * @method static Builder|OrderAgentNote whereCreatedAt($value)
 * @method static Builder|OrderAgentNote whereId($value)
 * @method static Builder|OrderAgentNote whereMessage($value)
 * @method static Builder|OrderAgentNote whereOrderId($value)
 * @method static Builder|OrderAgentNote whereUpdatedAt($value)
 * @mixin Eloquent
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

    public function isMessageEmojies()
    {
        return
            strlen(iconv('utf-8', 'utf-16le', $this->message)) / 2 <= 6
            &&
            Controller::hasEmojis($this->message);
    }
}
