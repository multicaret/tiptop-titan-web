<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewRecord extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * The sender instance.
     *
     * @var Order
     */
    public $newRecord;
    public $previousRecord;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($newRecord,$previousRecord)
    {
        $this->newRecord = $newRecord;
        $this->previousRecord = $previousRecord;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.newRecord')
                    ->subject('🎉🎊🎉CONGRATULATION! TipTop New Orders Record');
    }
}
