<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    private $code;
    private $name;
    public function __construct($code, $name)
    {
        $this->code = $code;
        $this->name = $name;

    }

    
    public function build()
    {
        return $this->subject('Password Reset')
        ->view('emails.reset-password', ['name' =>$this->name, 'code' => $this->code]);
    }
}
