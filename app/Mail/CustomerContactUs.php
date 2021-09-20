<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerContactUs extends Mailable
{
    use Queueable, SerializesModels;
    public $textemail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($textemail)
    {
        $this->textemail = $textemail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.CustomerContactUs');
    }
}
