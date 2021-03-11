<?php

namespace App\Mail;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Welcome extends Mailable implements ShouldQueue
{
    use SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @param      $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.welcome')
                    ->replyTo($this->user['email'])
                    ->subject(trans('Welcome to').' '.config('app.name'));
    }
}
