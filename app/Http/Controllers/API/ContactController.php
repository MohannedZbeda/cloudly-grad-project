<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Error;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'name' => 'required|string',
                'title' => 'required|string',
                'body' => 'required|string'
            ]);
            if ($validator->fails())
                return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);

            DB::transaction(function () use($request) {
                $contact = new Contact();
                $contact->email = $request->email;
                $contact->name = $request->name;
                $contact->title = $request->title;
                $contact->body = $request->body;
                $contact->save();
                Mail::to(env('INFO_MAIL'))->send(new \App\Mail\NotifyContactMail($contact->name, $contact->email, $contact->title, $contact->body));
            });
            DB::commit();
            return response()->json(['status_code' => 200, 'message', 'Message Recieved'])->setStatusCode(200);
        } catch (Error $error) {
            DB::rollBack();
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'ContactAPIController, Trying to store a contact message'])->setStatusCode(500);
        }
    }
}
