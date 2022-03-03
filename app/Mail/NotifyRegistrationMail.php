<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    private $name;
    public function __construct($name)
    {
        $this->name = $name;
    }

    
    public function build()
    {
        return $this->subject('Thank You For Subscribing To Our Newsletter')
        ->view('emails.notify-registration', ['name' =>$this->name]);
    }
}
