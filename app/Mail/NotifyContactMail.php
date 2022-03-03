<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyContactMail extends Mailable
{
    use Queueable, SerializesModels;

    private $name;
    private $email;
    private $message_title;
    private $message_body;
    public function __construct($name, $email, $message_title, $message_body)
    {
        $this->name = $name;
        $this->email = $email;
        $this->message_title = $message_title;
        $this->message_body = $message_body;
       

    }

    
    public function build()
    {
        return $this->subject('A New Contact')
        ->view('emails.notify-contact', ['name' =>$this->name, 'email' => $this->email, 'message_title' => $this->message_title, 'message_body' => $this->message_body]);
    }
}
