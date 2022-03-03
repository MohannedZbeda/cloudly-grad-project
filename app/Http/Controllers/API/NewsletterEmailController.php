<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\NewsletterEmail;
use Illuminate\Support\Facades\Mail;
use Error;
use Illuminate\Http\Request;

class NewsletterEmailController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|unique:newsletter_emails,email'
            ]);
            if ($validator->fails())
                return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);

            DB::transaction(function () use($request) {
                $newsletter_email = new NewsletterEmail();
                $newsletter_email->email = $request->email;
                $newsletter_email->name = $request->name;
                $newsletter_email->save();
                Mail::to($newsletter_email->email)->send(new \App\Mail\NotifyRegistrationMail($newsletter_email->name));

            });
            DB::commit();
            return response()->json(['status_code' => 200, 'message' => 'Data Registered'])->setStatusCode(200);
        } catch (Error $error) {
            DB::rollBack();
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'ContactAPIController, Trying to store a contact message'])->setStatusCode(500);
        }
    }
    }

