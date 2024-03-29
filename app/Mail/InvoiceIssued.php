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


    private Invoice $invoice;
    private User $user;
    public function __construct(Invoice $invoice, User $user)
    {
        $this->invoice = $invoice;
        $this->user = $user;
    }


    public function build()
    {
        return $this->subject('Cloudly Invoice')
            ->view('emails.invoice-issued')->with(['invoice' => $this->invoice, 'user' => $this->user]);
    }
}
