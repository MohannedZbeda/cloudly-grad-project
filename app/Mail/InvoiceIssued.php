<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceIssued extends Mailable
{
    use Queueable, SerializesModels;

    
    private $name;
    private $file;
    public function __construct($name, $file)
    {
        $this->name = $name;
        $this->file = $file;
       

    }

    
    public function build()
    {
        return $this->subject('TSIC Product Invoice')
        ->view('emails.invoice-issued', ['name' =>$this->name])->attach($this->file, [
            'as' => 'invoice.pdf',
            'mime' => 'application/pdf'
        ]);
    }
}
