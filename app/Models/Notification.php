<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    const LED_COLOR = '07604E';

    /**
     * @return mixed
     */
    public function type()
    {
        return last(explode('\\', $this->type));
    }
}
