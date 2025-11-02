<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Mail\SenderMail;
use App\Http\Requests\ContactRequest;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function __invoke(ContactRequest $request)
    {
        Mail::to(env('USER_EMAIL'))->send(new ContactMail($request->name, $request->email, $request->body));
        Mail::to($request->email)->send(new SenderMail($request->name, $request->email, $request->body));

        return redirect()->back();
    }
}
