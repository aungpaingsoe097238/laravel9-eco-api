<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CardSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public $stock_quantity;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($stock_quantity)
    {
        $this->stock_quantity = $stock_quantity;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Mail.cardSuccess');
    }
}
