<?php

namespace App\Mail;

use App\Http\Resources\API\InvoiceResource;
use App\Http\Resources\UserResource;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceIssued extends Mailable
{
    use Queueable, SerializesModels;

    
    private $invoice;
    private $user;
    public function __construct(Invoice $invoice, User $user)
    {
        $this->invoice = $invoice;
        $this->user = $user;
       

    }

    
    public function build()
    {
        return $this->subject('TSIC Product Invoice')
        ->view('emails.invoice-issued', ['invoice' => new InvoiceResource($this->invoice), 'user' => new UserResource($this->user->with('info'))]);
    }
}
