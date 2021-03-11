<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Contact extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The sender instance.
     *
     * @var Order
     */
    public $sender;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sender)
    {
        $this->sender = $sender;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.contact')
                    ->replyTo($this->sender['email'], $this->sender['name'])
                    ->subject(config('app.name').' - لديك رسالة جديدة');
    }
}
