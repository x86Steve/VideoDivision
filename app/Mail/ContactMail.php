<?php

namespace App\Mail;

use App\Http\Controllers\ContactController;
use App\Http\Requests\ContactRequest;
use http\Env\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class ContactMail extends Mailable
{
    use Queueable, SerializesModels;
    public $request;

    public function __construct($request)

    {
        $this->request = $request;
    }

    public function build()
    {

        return $this ->view('email.new-contact');
    }
}
