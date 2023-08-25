<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $user_id;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("this is the verifcation code")->view('pages.verification.verificationEmail');
    }
}