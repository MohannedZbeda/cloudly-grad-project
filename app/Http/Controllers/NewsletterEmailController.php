<?php

namespace App\Http\Controllers;

use App\Models\NewsletterEmail;
use Error;
class NewsletterEmailController extends Controller
{
    public function index()
    {
        try {
            $emails = NewsletterEmail::get()
            ->map
            ->only(['name', 'email']);
            return response()->json(['status_code' => 200, 'emails' => $emails])->setStatusCode(200);
        } catch (Error $error) {
            return response()->json(['status_code' => 500, 'location' => 'NewsletterEmailController, Trying to get all emails', 'error' => $error])->setStatusCode(500);
           
        }
    }
}
