<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class TestMail extends Mailable
{
    public function build()
    {
        return $this->subject('IT Help Desk - Email Test')
                    ->view('email.test');
    }
}
