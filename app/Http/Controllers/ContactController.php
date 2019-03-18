<?php

namespace App\Http\Controllers;
use App\Http\Requests\ContactRequest;
use Illuminate\Http\Request;
use Mail;
use App\Mail\ContactMail;
class ContactController extends Controller
{
    public function view()
    {
        return view('contact');
    }
    public function mail(ContactRequest $request)
    {

        Mail::to("admin@videodivision.net")->send(new ContactMail($request));
        return redirect()->back()->with('status','Your message has been sent. Please wait 1 hour for a response.');
    }
}

